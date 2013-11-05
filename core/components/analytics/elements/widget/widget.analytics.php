<?php
/**
 * @var modX $modx
 */
/* load the analytics lexicon into the JS lexicon */
//load class
$corePath = $modx->getOption('analytics.core_path',null,$modx->getOption('core_path').'components/analytics/');
require_once $corePath.'model/analytics/analytics.class.php';
$ga = new GoogleAnalytics($modx);
$modx->controller->addLexiconTopic('analytics:default');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">var GA = {connector_url:"'.$ga->config['connectorUrl'].'",assets_url:"'.$ga->config['assetsUrl'].'"};</script>');

$sitename = $modx->getOption('analytics_sitename');

//Get the amount of days
$days = $modx->getOption('analytics_days',null,7);
//Get the settings
$settings = array(
    'sessionToken'=> trim($modx->getOption('analytics_sessionToken')),
    'profileId'=> trim($modx->getOption('analytics_profileId')),
    'accountId'=> trim($modx->getOption('analytics_accountId')),
    'webPropertyId'=> trim($modx->getOption('analytics_webPropertyId')),
    'start_date' => date('Y-m-d', strtotime('-'.($days-1).' day',time())),
    'end_date' => date('Y-m-d'),
);


//load lexicon files
$modx->getService('lexicon','modLexicon');
$modx->lexicon->load('analytics:default');
//lexicon to js
$lexicon = $modx->lexicon->fetch($prefix = 'analytics.',$removePrefix = true);
$lexiconJs = $modx->toJSON($lexicon);
$modx->smarty->assign('_langs', $lexicon);

if ($_REQUEST['token'])
{
	$sessiontoken = $ga->getSessionToken($_REQUEST['token']);
	$Setting = $modx->getObject('modSystemSetting', 'analytics_sessionToken');
	$Setting->set('value', trim($sessiontoken));
	$Setting->save();
	$settings['sessionToken'] = trim($sessiontoken);
	unset($_REQUEST['token']);
}

if(empty($settings['sessionToken'])){
	$modx->smarty->assign('_langs', $lexicon);
	$modx->smarty->assign('authUrl','https://www.google.com/accounts/AuthSubRequest?next='.$ga->fullUrl().'&scope=https://www.google.com/analytics/feeds/&secure=0&session=1');
	return $modx->smarty->fetch($ga->config['elementsPath'].'tpl/widget.auth.tpl');
}

if (!empty($_POST['siteSelect'])) {
    list($sitename, $accountId, $profileId, $webPropertyId) = explode("|", $_POST['siteSelect']);
    /** @var modSystemSetting $setting */
    $setting = $modx->getObject('modSystemSetting', 'analytics_profileId');
    $settings['profileId'] = trim($profileId);
    $setting->set('value', trim($profileId));
    $setting->save();

    $setting = $modx->getObject('modSystemSetting', 'analytics_accountId');
    $settings['accountId'] = trim($accountId);
    $setting->set('value', trim($accountId));
    $setting->save();

    $setting = $modx->getObject('modSystemSetting', 'analytics_webPropertyId');
    $settings['webPropertyId'] = trim($webPropertyId);
    $setting->set('value', trim($webPropertyId));
    $setting->save();

    $modx->cacheManager->delete($days.'-analytics');
    unset($_POST['siteSelect']);
    //echo '<META HTTP-EQUIV=Refresh CONTENT="1; URL='.$modx->getOption('manager_url').'">';
}

//retrieve profiles
$profilesxml = $ga->callApi($settings['sessionToken'], 'https://www.googleapis.com/analytics/v2.4/management/accounts/~all/webproperties/~all/profiles');
$profiles = $ga->parseAccountList($profilesxml);
$modx->smarty->assign('profiles', $profiles);

//check if profile isset
if (empty($settings['profileId']) || empty($settings['accountId']) || empty($settings['webPropertyId'])){
	$modx->smarty->assign('managerUrl',$modx->getOption('manager_url'));
    return $modx->smarty->fetch($ga->config['elementsPath'].'tpl/widget.profile.tpl');
}

//load header scripts
$modx->regClientCSS($ga->config['assetsUrl'].'css/analytics.panel.widget.css');
$modx->regClientStartupScript($ga->config['assetsUrl'].'js/analytics.panel.widget.js');
$modx->regClientStartupScript('<script type="text/javascript">var days = '.$days.';</script>');

$modx->getCacheManager();
$analytics = $modx->cacheManager->get($days.'-analytics');

if (empty($analytics)) {
    //Retrieve all the data as xml
    $toplandingspagesxml = $ga->callApi($settings['sessionToken'], 'https://www.google.com/analytics/feeds/data?ids='.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3ApagePath&metrics=ga%3Aentrances%2Cga%3Abounces%2Cga%3AentranceBounceRate%2Cga%3Aexits&sort=-ga:entrances');

    $topexitpagesxml = $ga->callApi($settings['sessionToken'], 'https://www.google.com/analytics/feeds/data?ids='.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3ApagePath&metrics=ga%3Aexits%2Cga%3Apageviews%2Cga%3AexitRate&sort=-ga:exits');

    $keywordsxml = $ga->callApi($settings['sessionToken'], 'https://www.google.com/analytics/feeds/data?ids='.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3Akeyword&metrics=ga%3Avisits%2Cga%3ApageviewsPerVisit%2Cga%3AavgTimeOnSite%2Cga%3ApercentNewVisits%2Cga%3AvisitBounceRate&sort=-ga:visits');

    $sitesearchxml = $ga->callApi($settings['sessionToken'], 'https://www.google.com/analytics/feeds/data?ids='.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3AsearchKeyword&metrics=ga%3AsearchUniques%2Cga%3AsearchResultViews%2Cga%3AsearchExitRate%2Cga%3AsearchDuration%2Cga%3AsearchDepth&sort=-ga:searchUniques');

    $trafficsourcesxml = $ga->callApi($settings['sessionToken'], 'https://www.google.com/analytics/feeds/data?ids='.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3Asource&metrics=ga%3Avisits%2Cga%3Avisitors%2Cga%3ApageviewsPerVisit%2Cga%3AavgTimeOnSite%2Cga%3ApercentNewVisits%2Cga%3AvisitBounceRate&sort=-ga:visits');

    $generalxml = $ga->callApi($settings['sessionToken'], 'https://www.google.com/analytics/feeds/data?ids='.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3Ayear&metrics=ga%3Avisits%2Cga%3Avisitors%2Cga%3Apageviews%2Cga%3ApageviewsPerVisit%2Cga%3AavgTimeOnSite%2Cga%3ApercentNewVisits%2Cga%3AvisitBounceRate&sort=-ga:visits');

    $visitscharxml = $ga->callApi($settings['sessionToken'], 'https://www.google.com/analytics/feeds/data?ids='.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3Adate&metrics=ga%3Avisits%2Cga%3Avisitors%2Cga%3Apageviews%2Cga%3ApageviewsPerVisit%2Cga%3AavgTimeOnSite%2Cga%3ApercentNewVisits%2Cga%3AvisitBounceRate&sort=ga:date');

    $devicescharxml = $ga->callApi($settings['sessionToken'], 'https://www.google.com/analytics/feeds/data?ids='.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3AoperatingSystem&metrics=ga%3Avisits&sort=ga:visits');

    $mobilecharxml = $ga->callApi($settings['sessionToken'], 'https://www.google.com/analytics/feeds/data?ids='.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3AisMobile&metrics=ga%3Avisits&sort=ga:visits');

    $goalnamesxml = $ga->callApi($settings['sessionToken'], 'https://www.google.com/analytics/feeds/datasources/ga/accounts/'.$settings['accountId'].'/webproperties/'.$settings['webPropertyId'].'/profiles/~all/goals');


    //xml data to array
    $toplandingspages = $ga->parseData($toplandingspagesxml);
    $topexitpages = $ga->parseData($topexitpagesxml);
    $keywords = $ga->parseData($keywordsxml);
    $sitesearch = $ga->parseData($sitesearchxml);
    $trafficsourceschar = $ga->parseData($trafficsourcesxml);
    $general = $ga->parseData($generalxml);
    $visits = $ga->parseData($visitscharxml);
    $goalnames = $ga->parseDataGoals($goalnamesxml);
    $deviceschar = $ga->parseData($devicescharxml);
    $mobilechar = $ga->parseData($mobilecharxml);

    //generate the goals api call
    foreach($goalnames as $goalname){
        $goalMetrics .= 'ga%3Agoal'.$goalname['id'].'Completions%2C';
    }
    $goalMetrics .= 'ga%3AgoalCompletionsAll';

    //retrieve goals as xml
    $goalsxml = $ga->callApi($settings['sessionToken'],'https://www.google.com/analytics/feeds/data?ids='.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3Adate&metrics='.$goalMetrics.'&sort=ga:date');

    //xml data to array
    $goals = $ga->parseData($goalsxml);

    //Make new array for goals
    $goalstable = array();
    foreach($goals as $goal){
        for($i = 1; $i <= count($goalnames); $i++){
            $goalstable['goal'.$i]['completions'] += $goal['oal'.$i.'Completions'];
            $goalstable['goal'.$i]['goalname'] =$goalnames[($i-1)]['goalname'];
        }
        $general[0]['allGoals'] += (int)$goal['oalCompletionsAll'];
    }

    //Make new array for the pie chart
	foreach($trafficsourceschar as $trafficsourc){
		if($trafficsourc['source'] == 'google' || $trafficsourc['source'] == 'search'){
	
			$trafficsourcessearch += $trafficsourc['visits'];
		}
		elseif($trafficsourc['source'] == '(direct)'){
			$trafficsourcesdirect += $trafficsourc['visits'];
		}
		else{
			$trafficsourcesreffered += $trafficsourc['visits'];
		}
	}
	
	$trafficsourceschararr = array();
	//$trafficsourceschararr[0]['name'] = 'Search Engines ('.round((100/$general[0]['visits'])*$trafficsourcessearch,2).'%)';
	$trafficsourceschararr[0]['name'] = 'Search Engines';
	$trafficsourceschararr[0]['visits'] = $trafficsourcessearch;
	//$trafficsourceschararr[1]['name'] = 'Direct Traffic ('.round((100/$general[0]['visits'])*$trafficsourcesdirect,2).'%)';
	$trafficsourceschararr[1]['name'] = 'Direct Traffic';
	$trafficsourceschararr[1]['visits'] = $trafficsourcesdirect;
	///$trafficsourceschararr[2]['name'] = 'Referring Sites ('.round((100/$general[0]['visits'])*$trafficsourcesreffered,2).'%)';
	$trafficsourceschararr[2]['name'] = 'Referring Sites';
	$trafficsourceschararr[2]['visits'] = $trafficsourcesreffered;
	
    //change the name of the devices array key field
    foreach($deviceschar as $device){

    }

    //assign to smarty
    $modx->smarty->assign('toplandingspages', $toplandingspages);
    $modx->smarty->assign('topexitpages', $topexitpages);
    $modx->smarty->assign('keywords', $keywords);
    $modx->smarty->assign('sitesearches', $sitesearch);
    $modx->smarty->assign('general', $general[0]);
    $modx->smarty->assign('visitsarr', $visits);
    $modx->smarty->assign('toptrafficsource', $trafficsourceschar);
    $modx->smarty->assign('goalnames', $goalnames);
    $modx->smarty->assign('goals', $goals);
    $modx->smarty->assign('goalstable', $goalstable);
    $modx->smarty->assign('header', $modx->getOption('site_name').' - ('.$settings['start_date'].' | '.$settings['end_date'].')');
    $modx->smarty->assign('days', $days);
    //all the data to array
    $analytics = array();
    $analytics['toplandingspages'] = $toplandingspages;
    $analytics['topexitpages'] = $topexitpages;
    $analytics['keywords'] = $keywords;
    $analytics['sitesearch'] = $sitesearch;
    $analytics['general'] = $general[0];
    $analytics['visitsarr'] = $visits;

    $analytics['visits'] = $visits;
    $analytics['profiles'] = $profiles;
    $analytics['trafficsourceschararr'] = $trafficsourceschararr;
    $analytics['devices'] = $deviceschar;
    $analytics['mobile'] = $mobilechar;

    $analytics['toptrafficsource'] = $trafficsourceschar;
    $analytics['goalnames'] = $goalnames;
    $analytics['goals'] = $goals;
    $analytics['goalstable'] = $goalstable;
    $analytics['header'] = $modx->getOption('site_name').' - ('.$settings['start_date'].' | '.$settings['end_date'].')';
    $analytics['days'] = $days;

    //array to cache
    $modx->cacheManager->set($days.'-analytics',$analytics,$modx->getOption('analytics_cachingtime',null,3600));

} else {
	foreach ($analytics as $k => $analyticsdata) {
	    $modx->smarty->assign($k, $analyticsdata);
	}
}
return $modx->smarty->fetch($ga->config['elementsPath'].'tpl/widget.analytics.tpl');