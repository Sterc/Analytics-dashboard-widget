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

	public function getSessionToken($onetimetoken) {
		$output = $this->callApi($onetimetoken, "https://www.google.com/accounts/AuthSubSessionToken");
		if (preg_match("/Token=(.*)/", $output, $matches))
		{
			$sessionToken = $matches[1];
		} else {
			echo "Error authenticating with Google.";
			exit;
		}

		return $sessionToken;
	}

	/**
     * @param string $sessionToken
     * @param string $url
     * @return mixed
     */
	public function callApi($token,$url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$curlheader[0] = sprintf("Authorization: AuthSub token=\"%s\"/n", $token);
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
					$results[$i][ltrim($dimension->getAttribute("name"),"ga:")] =  $value;
				} else {
					$results[$i][ltrim($dimension->getAttribute("name"),"ga:")] =  $dimension->getAttribute('value');
				}
			}

			$metrics = $entry->getElementsByTagName('metric');
			/** @var DOMElement $metric */
			foreach ($metrics as $metric) {
				if ($metric->getAttribute('name') == 'ga:avgTimeOnSite'){
					$results[$i][ltrim($metric->getAttribute('name'),"ga:")] =  $this->secondMinute($metric->getAttribute('value'));
				} else {
					$results[$i][ltrim($metric->getAttribute('name'),"ga:")] =  $metric->getAttribute('value');
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
	public function parseDataGoals($xml) {
		$doc = new DOMDocument();
		$doc->loadXML($xml);

		$entries = $doc->getElementsByTagName('entry');
		$i = 0;
		$results = array();
		/** @var DOMElement $entry */
		foreach ($entries as $entry) {
            $goals = $entry->getElementsByTagName('goal');
            /** @var DOMElement $goal */
            foreach ($goals as $goal) {
                $results[$i]['id'] = $goal->getAttribute('number');
                $results[$i]['goalname'] = $goal->getAttribute('name');
            }
            $i++;
		}
		return $results;
    }

    /**
     * Returns accounts list as array
     * @param string $xml
     * @return array
     */
	public function parseAccountList($xml){
		$doc = new DOMDocument();
		if(stripos($xml,"<") !== FALSE)
		{
			$doc->loadXML($xml);
			$entries = $doc->getElementsByTagName('entry');
			$i = 0;
			$profiles= array();
			foreach($entries as $entry)
			{
				$profiles[$i] = array();

				$title = $entry->getElementsByTagName('title');
				$profiles[$i]["title"] = $title->item(0)->nodeValue;

				$entryid = $entry->getElementsByTagName('id');
				$profiles[$i]["entryid"] = $entryid->item(0)->nodeValue;

				// $tableId = $entry->getElementsByTagName('tableId');
				// $profiles[$i]["tableId"] = $tableId->item(0)->nodeValue;
				
				$properties = $entry->getElementsByTagName('property');
			        foreach($properties as $property) {
						if($property->getAttribute('name') == 'ga:accountId'){
								$profiles[$i]["accountId"] = $property->getAttribute('value');
						}
						if($property->getAttribute('name') == 'ga:webPropertyId'){
								$profiles[$i]["webPropertyId"] = $property->getAttribute('value');
						}
						if($property->getAttribute('name') == 'dxp:tableId'){
								$profiles[$i]["tableId"] = $property->getAttribute('value');
						}
			        }
				$i++;
			}
			return $profiles;
		} else {
			$sessionToken = "Authentication Failed.";
		}
        return array();
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

		//Fix for the strange bug of Google's authenticating 
		$domainParts = explode('.', $_SERVER['SERVER_NAME']);
		if($domainParts[0] == 'www'){
			$domainParts[1] = ucfirst($domainParts[1]);
		}else{
			$domainParts[0] = ucfirst($domainParts[0]);
		}

		return $protocol . "://" . implode('.', $domainParts) . $port . $_SERVER['REQUEST_URI'];
	}


}//end of class