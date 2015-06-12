<?php
/**
 * @var modX $modx
 */
/* load the analytics lexicon into the JS lexicon */
//load class
$corePath = $modx->getOption('analytics.core_path',null,$modx->getOption('core_path').'components/analytics/');
require_once $corePath.'model/analytics/analytics.class.php';
require_once($corePath.'model/google-api-php-client/src/Google/Client.php');
require_once($corePath.'model/google-api-php-client/src/Google/Service/Analytics.php');

$client_id = '697135090133-s51enie43b74v6qnpgfo8i23qv11gll6.apps.googleusercontent.com';
$client_secret = 'T5svt7HvpF3eYScE-HeCvdxh';
$developer_key = 'AIzaSyBQyj6t45IvALZA91L9aNwxX7Z1tHMu0Xs';

// new instance of client class
$client = new Google_Client();
$client->setApplicationName('Google Analytics Dashboard Widget');
$client->setClientId($client_id);
$client->setClientSecret($client_secret);
$client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
$client->setDeveloperKey($developer_key);
$client->setScopes(array('https://www.googleapis.com/auth/analytics.readonly'));
$client->setAccessType('offline');

$ga = new GoogleAnalytics($modx);
$modx->controller->addLexiconTopic('analytics:default');
$modx->regClientStartupHTMLBlock('<script type="text/javascript">var GA = {connector_url:"'.$ga->config['connectorUrl'].'",assets_url:"'.$ga->config['assetsUrl'].'"};</script>');

$sitename = $modx->getOption('analytics_sitename');

//Get the amount of days
$days = $modx->getOption('analytics_days',null,7);
//Get the settings
$settings = array(
    'refreshToken'=> trim($modx->getOption('analytics_refreshToken')),
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

if((isset($_POST['auth_code'])) && ($_POST['auth_code'] != '')){    // authorization code passed via POST on SELF
    $client->authenticate($_POST['auth_code']);                     // authorization code exchange for token
    $token = $client->getAccessToken();                             // store token in var 'token'
    $refreshToken = json_decode($token);
    $refreshToken = $refreshToken->refresh_token;                   // get the refresh token

    $client->setAccessToken($token);                                // set access token

    $Setting = $modx->getObject('modSystemSetting', 'analytics_refreshToken');
    $Setting->set('value', trim($refreshToken));
    $Setting->save();
    $settings['refreshToken'] = trim($refreshToken);


    $modx->getCacheManager();
    $modx->cacheManager->delete($days.'-analytics');

    $modx->cacheManager->deleteTree($modx->getOption('core_path',null,MODX_CORE_PATH).'cache/mgr/smarty/',array(
       'deleteTop' => false,
        'skipDirs' => false,
        'extensions' => array('.cache.php','.php'),
    ));
    $modx->reloadConfig();

}

if(empty($settings['refreshToken'])){
	$modx->smarty->assign('_langs', $lexicon);
    $modx->smarty->assign('authUrl',$client->createAuthUrl());
    $modx->smarty->assign('redirect_url',$client->createAuthUrl());

	return $modx->smarty->fetch($ga->config['elementsPath'].'tpl/widget.auth.tpl');
}

if (!empty($_POST['siteSelect'])) {
    list($sitename, $accountId, $profileId, $webPropertyId) = explode("|", $_POST['siteSelect']);

    //print_r(explode("|", $_POST['siteSelect']));exit;
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

    $modx->getCacheManager();
    $modx->cacheManager->delete($days.'-analytics');

    $modx->cacheManager->deleteTree($modx->getOption('core_path',null,MODX_CORE_PATH).'cache/mgr/smarty/',array(
       'deleteTop' => false,
        'skipDirs' => false,
        'extensions' => array('.cache.php','.php'),
    ));
    $modx->reloadConfig();

    unset($_POST['siteSelect']);
    //echo '<META HTTP-EQUIV=Refresh CONTENT="1; URL='.$modx->getOption('manager_url').'">';
}

$client->refreshToken($settings['refreshToken']);   // generate new access token with the refresh token
$token = $client->getAccessToken();
$client->setAccessToken($token);
$gsaCls = new Google_Service_Analytics($client);
//retrieve profiles
try {
    $accounts = $gsaCls->management_accountSummaries->listManagementAccountSummaries();
} catch (Exception $e) {

    $modx->smarty->assign('_langs', $lexicon);
    $modx->smarty->assign('authUrl',$client->createAuthUrl());
    $modx->smarty->assign('redirect_url',$client->createAuthUrl());
    $modx->smarty->assign('error',$e->getMessage());

    return $modx->smarty->fetch($ga->config['elementsPath'].'tpl/widget.auth.tpl');
}
//print_r($accounts);
$profiles = $ga->parseAccountList($accounts);
//print_r($profiles);exit;
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
//$analytics = $modx->cacheManager->get($days.'-analytics');

if (empty($analytics)) {
    //Retrieve all the data as xml
    $toplandingspagesxml = $ga->callApi($client->getAccessToken(), 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A'.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3ApagePath&metrics=ga%3Aentrances%2Cga%3Abounces%2Cga%3AentranceBounceRate%2Cga%3Aexits&sort=-ga:entrances');

    $topexitpagesxml = $ga->callApi($client->getAccessToken(), 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A'.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3ApagePath&metrics=ga%3Aexits%2Cga%3Apageviews%2Cga%3AexitRate&sort=-ga:exits');

    $keywordsxml = $ga->callApi($client->getAccessToken(), 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A'.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3Akeyword&metrics=ga%3Avisits%2Cga%3ApageviewsPerVisit%2Cga%3AavgTimeOnSite%2Cga%3ApercentNewVisits%2Cga%3AvisitBounceRate&sort=-ga:visits');

    $sitesearchxml = $ga->callApi($client->getAccessToken(), 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A'.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3AsearchKeyword&metrics=ga%3AsearchUniques%2Cga%3AsearchResultViews%2Cga%3AsearchExitRate%2Cga%3AsearchDuration%2Cga%3AsearchDepth&sort=-ga:searchUniques');

    $trafficsourcesxml = $ga->callApi($client->getAccessToken(), 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A'.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3Asource&metrics=ga%3Avisits%2Cga%3Avisitors%2Cga%3ApageviewsPerVisit%2Cga%3AavgTimeOnSite%2Cga%3ApercentNewVisits%2Cga%3AvisitBounceRate&sort=-ga:visits');

    $generalxml = $ga->callApi($client->getAccessToken(), 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A'.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3Ayear&metrics=ga%3Avisits%2Cga%3Avisitors%2Cga%3Apageviews%2Cga%3ApageviewsPerVisit%2Cga%3AavgTimeOnSite%2Cga%3ApercentNewVisits%2Cga%3AvisitBounceRate&sort=-ga:visits');

    $visitscharxml = $ga->callApi($client->getAccessToken(), 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A'.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3Adate&metrics=ga%3Avisits%2Cga%3Avisitors%2Cga%3Apageviews%2Cga%3ApageviewsPerVisit%2Cga%3AavgTimeOnSite%2Cga%3ApercentNewVisits%2Cga%3AvisitBounceRate&sort=ga:date');

    $devicescharxml = $ga->callApi($client->getAccessToken(), 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A'.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3AoperatingSystem&metrics=ga%3Avisits&sort=ga:visits');

    $mobilecharxml = $ga->callApi($client->getAccessToken(), 'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A'.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3AisMobile&metrics=ga%3Avisits&sort=ga:visits');

    $goalnamesxml = $ga->callApi($client->getAccessToken(), 'https://www.googleapis.com/analytics/v3/management/accounts/'.$settings['accountId'].'/webproperties/'.$settings['webPropertyId'].'/profiles/'.$settings['profileId'].'/goals?key='.$developer_key);

    //xml data to array
    $toplandingspages = $modx->fromJSON($toplandingspagesxml);
    $topexitpages = $modx->fromJSON($topexitpagesxml);
    $keywords = $modx->fromJSON($keywordsxml);
    $sitesearch = $modx->fromJSON($sitesearchxml);
    $trafficsourceschar = $modx->fromJSON($trafficsourcesxml);
    $general = $modx->fromJSON($generalxml);
    $visits = $modx->fromJSON($visitscharxml);
    $goalnames = $ga->parseDataGoals($goalnamesxml);
    $deviceschar = $modx->fromJSON($devicescharxml);
    $mobilechar = $modx->fromJSON($mobilecharxml);


    foreach ($visits['totalsForAllResults'] as $ktotal => $vtotal) {
        $visits['totalsForAllResults'][str_replace('ga:', '', $ktotal)] = $vtotal;
    }


    //generate the goals api call
    foreach($goalnames as $goalname){
        $goalMetrics .= 'ga%3Agoal'.$goalname['id'].'Completions%2C';
    }
    $goalMetrics .= 'ga%3AgoalCompletionsAll';

    //retrieve goals as xml
    $goalsxml = $ga->callApi($client->getAccessToken(),'https://www.googleapis.com/analytics/v3/data/ga?ids=ga%3A'.$settings['profileId'].'&start-date='.$settings['start_date'].'&end-date='.$settings['end_date'].'&dimensions=ga%3Adate&metrics='.$goalMetrics.'&sort=ga:date');

    //xml data to array
    $goals = $modx->fromJSON($goalsxml);
    // print_r($goals);exit;

    //Make new array for goals
    $goalstable = array();
//print_r($goals);
    $gi = 0;
    foreach($goalnames as $goalname){
        $goalstable[$gi]['completions'] += $goals['totalsForAllResults']['ga:goal'.$goalname['id'].'Completions'];
        $goalstable[$gi]['goalname'] = $goalname['goalname'];
        $gi++;
       // $general[0]['allGoals'] += (int)$goal['oalCompletionsAll'];
    }
    //print_r($trafficsourceschar);exit;
    //Make new array for the pie chart
	foreach($trafficsourceschar['rows'] as $trafficsourc){
		if($trafficsourc[0] == 'google' || $trafficsourc[0] == 'search' || $trafficsourc[0] == 'bing' || $trafficsourc[0] == 'yahoo'){
			$trafficsourcessearch += $trafficsourc[1];
		}
		elseif($trafficsourc[0] == '(direct)'){
			$trafficsourcesdirect += $trafficsourc[1];
		}
		else{
			$trafficsourcesreffered += $trafficsourc[1];
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

    //change the name of the mobile array key field
    $newmobilechar = array();
    foreach($mobilechar['rows'] as $mobile){
        if($mobile[0] == 'No'){
            $newmobilechar[] = array('key' => 'Desktop', 'visits' => $mobile[1]);
        }elseif($mobile[0] == 'Yes'){
            $newmobilechar[] = array('key' => 'Mobile', 'visits' => $mobile[1]);
        }
    }
    $mobilechar = $newmobilechar;
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
//print_r($analytics);exit;
    //array to cache
    $modx->cacheManager->set($days.'-analytics',$analytics,$modx->getOption('analytics_cachingtime',null,3600));

} else {
	foreach ($analytics as $k => $analyticsdata) {
	    $modx->smarty->assign($k, $analyticsdata);
	}
}
return $modx->smarty->fetch($ga->config['elementsPath'].'tpl/widget.analytics.tpl');