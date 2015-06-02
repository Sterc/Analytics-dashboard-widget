<?php
/**
 * @package Analytics
 */
class GoogleAnalytics {
  function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;
		
        $basePath = $this->modx->getOption('analytics.core_path',$config,$this->modx->getOption('core_path').'components/analytics/');
        $assetsUrl = $this->modx->getOption('analytics.assets_url',$config,$this->modx->getOption('assets_url').'components/analytics/');
        $this->config =array(
            'basePath' => $basePath,
            'corePath' => $basePath,
            'modelPath' => $basePath.'model/',
            'processorsPath' => $basePath.'processors/',
            'elementsPath' => $basePath.'elements/',
            'chunksPath' => $basePath.'elements/chunks/',
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl.'js/',
            'cssUrl' => $assetsUrl.'css/',
            'assetsUrl' => $assetsUrl,
            'connectorUrl' => $assetsUrl.'connector.php'
        );
        
    }

	/**
     * @param string $sessionToken
     * @param string $url
     * @return mixed
     */
	public function callApi($token,$url){
		$accessToken = (array)json_decode($token);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url.'&access_token='.$accessToken['access_token']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $curlheader);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
    }

		
		
		
		
    /**
     * Returns data as array
     *
     * @param mixed $xml
     * @return array
     */
	public function parseData($xml){
		return (array)json_decode($xml);
		$doc = new DOMDocument();
		$doc->loadXML($xml);

		$entries = $doc->getElementsByTagName('entry');
		$i = 0;
		$results = array();
		/** @var DOMElement $entry */
		foreach ($entries as $entry) {
			$dimensions = $entry->getElementsByTagName('dimension');
			/** @var DOMElement $dimension */
			foreach ($dimensions as $dimension) {
				if ($dimension->getAttribute("name") == 'ga:isMobile' || $dimension->getAttribute("name") == 'ga:date' ){
					if ($dimension->getAttribute('value') == 'Yes'){
						$value = 'Mobile';
					} elseif ($dimension->getAttribute('value') == 'No'){
						$value = 'Desktop';
					} else {
						$value = date('Y-m-d',strtotime($dimension->getAttribute('value')));
					}
					$results[$i][ltrim($dimension->getAttribute("name"),"ga:")] = $value;
				} else {
					$results[$i][ltrim($dimension->getAttribute("name"),"ga:")] = $dimension->getAttribute('value');
				}
			}

			$metrics = $entry->getElementsByTagName('metric');
			/** @var DOMElement $metric */
			foreach ($metrics as $metric) {
				if ($metric->getAttribute('name') == 'ga:avgTimeOnSite'){
					$results[$i][ltrim($metric->getAttribute('name'),"ga:")] = $this->secondMinute($metric->getAttribute('value'));
				} else {
					$results[$i][ltrim($metric->getAttribute('name'),"ga:")] = $metric->getAttribute('value');
				}
			}

			$i++;
		}
		return $results;
    }
    
    /**
     * @param string $xml
     * @return array
     */
	public function parseDataGoals($goals) {
		$data = $this->modx->fromJSON($goals);
		$i = 0;
		$results = array();
		/** @var DOMElement $entry */
		foreach ($data['items'] as $goal) {
            $results[$i]['id'] = $goal['id'];
            $results[$i]['goalname'] = $goal['name'];
            $i++;
		}
		return $results;
    }

    /**
     * Returns accounts list as array
     * @param string $xml
     * @return array
     */
	public function parseAccountList($accounts){
		$output = array();
        $items = $accounts->getItems();
        $i = 0;
        if(count($items) > 0) {
            foreach ($items as $item) { 
            	// print_r($item);
            	// exit;
                $wps = $item->getWebProperties();
                if(count($wps) > 0){
                    foreach($wps as $wp){   
		            	$views = $wp->getProfiles();                                                    
                        if((!is_null($views)) && (count($views) > 0)){
                            foreach($views as $view){
		                        $output[$i] = array();
				            	$output[$i]["title"] = $item->getName().' - '.$view->getName();
				            	$output[$i]["accountId"] = $item->getId();
				            	$output[$i]["profileId"] = $view->getId();
				            	$output[$i]["webPropertyId"] = $wp->getId();
				                $i++;
							}                        
                        }
                    }
                }
            }
        }

        return $output;
	}

    /**
     * @param int $seconds
     * @return string
     */
    public function secondMinute($seconds) {
        /// get minutes
        $minResult = floor($seconds/60);

        /// if minutes is between 0-9, add a "0" --> 00-09
        if($minResult < 10){$minResult = 0 . $minResult;}

        /// get sec
        $secResult = ($seconds/60 - $minResult)*60;
        $secResult = round($secResult);
        /// if secondes is between 0-9, add a "0" --> 00-09
        if($secResult < 10){$secResult = 0 . $secResult;}

        /// return result
        return $minResult.":".$secResult;

    }

	public function fullUrl() {
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);

		return $protocol . "://" . $_SERVER['HTTP_HOST'] . $port . $_SERVER['REQUEST_URI'];
	}


}//end of class