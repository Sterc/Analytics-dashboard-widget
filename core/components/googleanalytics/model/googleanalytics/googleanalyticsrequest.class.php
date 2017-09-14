<?php

	/**
	 * Google Analytics
	 *
	 * Copyright 2017 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
	 *
	 * Google Analytics is free software; you can redistribute it and/or modify it under
	 * the terms of the GNU General Public License as published by the Free Software
	 * Foundation; either version 2 of the License, or (at your option) any later
	 * version.
	 *
	 * Google Analytics is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Google Analytics; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */

	class GoogleAnalyticsRequest {
		const API_URL 						= 'https://www.googleapis.com/analytics/v3/';
        const API_OAUTH_URL                 = 'https://accounts.google.com/o/oauth2/auth';
		const API_OAUTH_TOKEN_URL 			= 'https://accounts.google.com/o/oauth2/token';
		const API_OAUTH_TOKEN_STATUS_URL	= 'https://accounts.google.com/o/oauth2/tokeninfo';
		const API_OAUTH_REDIRECT_URL        = 'urn:ietf:wg:oauth:2.0:oob';
		
		/**
		 * @access public.
		 * @var Object.
		 */
		public $modx;
		
	    /**
		 * @access public.
		 * @var Object.
		 */
		public $googleanalytics;
		
		/**
		 * @access public.
		 * @var Integer.
		 */
		public $history = 7;
		
		/**
		 * @access public.
		 * @param Object $modx.
		 * @param object $googleanalytics.
		 */
		public function __construct(modX &$modx, &$googleanalytics) {
			$this->modx =& $modx;
			$this->googleanalytics =& $googleanalytics;
		}
		
		/**
		 * @access public.
		 * @param Integer $history.
		 */
		public function setHistory($history) {
			$this->history = $history;
		}
		
		/**
		 * @access public.
		 * @return Integer.
		 */
		public function getHistory() {
			return $this->history;
		}
		
		/**
		 * @access public.
		 * @return String.
		 */
		public function getApiKey() {
			return $this->modx->getOption('googleanalytics.client_id');
		}
		
		/**
		 * @access public.
		 * @return String.
		 */
		public function getApiSecret() {
			return $this->modx->getOption('googleanalytics.client_secret');
		}

        /**
         * @access public.
         * @return String.
         */
		public function getAuthorizeUrl() {
		    return GoogleAnalyticsRequest::API_OAUTH_URL.'?'.http_build_query(array(
                'response_type' => 'code',
                'client_id'     => $this->getApiKey(),
                'redirect_uri'  => GoogleAnalyticsRequest::API_OAUTH_REDIRECT_URL,
                'scope'         => 'https://www.googleapis.com/auth/analytics.readonly'
            ));
        }
		
		/**
		 * @access public.
         * @param String $code.
		 * @return Array|Boolean.
		 */
		public function getAuthorizeTokens($code) {
            $parameters = array(
                'code'          => $code,
                'client_id'     => $this->getApiKey(),
                'client_secret' => $this->getApiSecret(),
                'redirect_uri'  => GoogleAnalyticsRequest::API_OAUTH_REDIRECT_URL,
                'scope'         => 'https://www.googleapis.com/auth/analytics.readonly',
                'grant_type'    => 'authorization_code'
            );

            if (false !== ($token = $this->requestApi(GoogleAnalyticsRequest::API_OAUTH_TOKEN_URL, $parameters, 'POST'))) {
                return $token;
            }

            return false;
        }

        /**
         * @access public.
         * @param String $code.
         * @return String|Boolean.
         */
		public function getAccessToken() {
            $token = $this->modx->getCacheManager()->get('access_token', array(
                xPDO::OPT_CACHE_KEY => 'googleanalytics'
            ));

            if (empty($token)) {
                $parameters = array(
                    'refresh_token' 	=> $this->modx->getOption('googleanalytics.refresh_token', null, ''),
                    'client_id' 		=> $this->getApiKey(),
                    'client_secret' 	=> $this->getApiSecret(),
                    'redirect_uri'		=> GoogleAnalyticsRequest::API_OAUTH_REDIRECT_URL,
                    'grant_type'		=> 'refresh_token'
                );

                if (false !== ($tokens = $this->requestApi(GoogleAnalyticsRequest::API_OAUTH_TOKEN_URL, $parameters, 'POST'))) {
                    if (isset($tokens['access_token'])) {
                        $this->modx->getCacheManager()->set('access_token', $tokens['access_token'], $tokens['expires_in'], array(
                            xPDO::OPT_CACHE_KEY => 'googleanalytics'
                        ));

                        $token = $tokens['access_token'];
                    }
                }
            }

            return $token;
		}
		
		/**
		 * @access public.
		 * @return Array|Boolean.
		 */
		public function isAuthorized() {
		    if (!empty($this->modx->getOption('googleanalytics.refresh_token', null, ''))) {
                return $this->requestApi(GoogleAnalyticsRequest::API_OAUTH_TOKEN_STATUS_URL, array(
                    'access_token'	=> $this->getAccessToken()
                ));
            }

            return false;
		}
		
		/**
		 * @access public.
		 * @return String.
		 */
		public function getUserAgent() {
			return $this->modx->getOption('googleanalytics.api_useragent');
		}
			
		/**
		 * @access public.
		 * @return Array|Boolean.
		 */
		public function getAccounts() {
			return $this->request('management/accounts');
		}
		
		/**
		 * @acces public.
		 * @param String $account.
		 * @return Array|Boolean.
		 */
		public function getAccountProperties($account) {
			return $this->request('management/accounts/'.$account.'/webproperties');
		}
		
		/**
		 * @acces public.
		 * @param String $account.
		 * @param String $property.
		 * @return Array|Boolean.
		 */
		public function getAccountProfiles($account, $property) {
			return $this->request('management/accounts/'.$account.'/webproperties/'.$property.'/profiles');
		}
		
		/**
		 * @acces public.
		 * @param String $account.
		 * @param String $property.
		 * @param String $profile.
		 * @return Array|Boolean.
		 */
		public function getAccountProfile($account, $property, $profile) {
			return $this->request('management/accounts/'.$account.'/webproperties/'.$property.'/profiles/'.$profile);
		}
		
		/**
		 * @access public.
		 * @param String $type.
		 * @param String $parameters.
		 * @return Array.
		 */
		public function getData($type, $parameters = array()) {
			if (false !== ($account = $this->googleanalytics->getAuthorizedProfile())) {
				if (false !== strstr($type, 'realtime')) {
					$url = 'data/realtime';
				} else {
					$url = 'data/ga';
				}
				
				$parameters = array_merge(array(
					'ids'			=> 'ga:'.$account['id'],
					'start-date'	=> date('Y-m-d', strtotime('-'.($this->getHistory() - 1).' days')),
					'end-date'		=> date('Y-m-d'),
				), $parameters);
				
				switch ($type) {
					case 'meta':
					case 'meta-summary':
						$parameters = array_merge($parameters, array(
							'metrics'		=> 'ga:visits,ga:visitors,ga:pageviews,ga:uniquePageviews,ga:percentNewVisits,ga:exitRate,ga:pageviewsPerVisit,ga:avgSessionDuration,ga:visitBounceRate',
							'dimensions'	=> 'ga:date',
							'sort'			=> 'ga:date',
						));
									
						break;
					case 'visits':
						$parameters = array_merge($parameters, array(
							'metrics'		=> 'ga:visits,ga:visitors,ga:pageviews,ga:pageviewsPerVisit,ga:avgSessionDuration,ga:percentNewVisits,ga:visitBounceRate',
							'dimensions'	=> 'ga:date',
							'sort'			=> 'ga:date'
						));
									
						break;
					case 'visiters':
						$parameters = array_merge($parameters, array(
							'metrics'		=> 'ga:visits',
							'dimensions'	=> 'ga:visitorType',
							'sort'			=> 'ga:visits'
						));
						
						break;
					case 'language':
						$parameters = array_merge($parameters, array(
							'metrics'		=> 'ga:visits,ga:visitors,ga:pageviews',
							'dimensions'	=> 'ga:language',
							'sort'			=> '-ga:visits'
						));
					
						break;
					case 'country':
						$parameters = array_merge($parameters, array(
							'metrics'		=> 'ga:visits,ga:visitors,ga:pageviews',
							'dimensions'	=> 'ga:country',
							'sort'			=> '-ga:visits'
						));
					
						break;
					case 'devices':
						$parameters = array_merge($parameters, array(
							'metrics'		=> 'ga:visits',
							'dimensions'	=> 'ga:deviceCategory',
							'sort'			=> 'ga:visits'
						));
					
						break;
					case 'sources':
					case 'sources-summary':
						$parameters = array_merge($parameters, array(
							'metrics'		=> 'ga:visits,ga:visitors,ga:pageviewsPerVisit,ga:avgSessionDuration,ga:percentNewVisits,ga:visitBounceRate',
							'dimensions'	=> 'ga:source',
							'sort'			=> '-ga:visits'
						));
						
						break;
					case 'content-high':
						$parameters = array_merge($parameters, array(
							'metrics'		=> 'ga:entrances,ga:bounces,ga:entranceBounceRate,ga:exits',
							'dimensions'	=> 'ga:pagePath',
							'sort'			=> '-ga:entrances'
						));
				
						break;
					case 'content-low':
						$parameters = array_merge($parameters, array(
							'metrics'		=> 'ga:exits,ga:bounces,ga:pageviews,ga:exitRate',
							'dimensions'	=> 'ga:pagePath',
							'sort'			=> '-ga:exits'
						));
				
						break;
					case 'content-search':
						$parameters = array_merge($parameters, array(
							'metrics'		=> 'ga:visits,ga:pageviewsPerVisit,ga:avgSessionDuration,ga:percentNewVisits,ga:visitBounceRate',
							'dimensions'	=> 'ga:keyword',
							'sort'			=> '-ga:visits'
						));
				
						break;
                    case 'goals':
                        $parameters = array_merge($parameters, array(
                            'metrics'		=> 'ga:goalStartsAll,ga:goalCompletionsAll',
                            'dimensions'	=> 'ga:goalCompletionLocation',
                            'sort'			=> '-ga:goalCompletionsAll'
                        ));

                        break;
					case 'realtime':
						$parameters = array_merge($parameters, array(
							'metrics'		=> 'rt:activeUsers',
							'dimensions'	=> 'rt:userType'
						));
				
						break;
				}

				if (false !== ($data = $this->request($url, $parameters))) {
					return $this->parseData($data, $type);
				}
			}

			return array();
		}
		
		/**
		 * @access pubic.
		 * @param Array $data.
		 * @param String $type.
		 * @return Array.
		 */
		public function parseData($data, $type) {
			$output = array();
			$totals = array();

			if (isset($data['totalsForAllResults'])) {
			    $totals = $data['totalsForAllResults'];
            }
			
			if (false !== strstr($type, 'meta')) {
                $output = $totals;

				switch ($type) {
                    case 'meta-summary':
                        $output = $this->parseDataMeta($output);

                        break;
                }
			} else {	
				if (isset($data['rows'])) {
					foreach ($data['rows'] as $value) {
						$row = array();
	
						foreach ($value  as $key => $column) {
							$key = substr($data['columnHeaders'][$key]['name'], 3, strlen($data['columnHeaders'][$key]['name']));
	
							switch ($key) {
								case 'date':
									$row[$key] = date('Y-m-d 00:00:00', strtotime($column));

									$row[$key.'_short'] = strftime('%d %b', strtotime($column));
									$row[$key.'_long'] = strftime('%A %d %B %Y', strtotime($column));
									
									break;
								case 'visitorType':
									$row[$key] = $this->modx->lexicon('googleanalytics.'.str_replace(' ', '_', strtolower($column)));
									
									break;
								case 'deviceCategory':
									$row[$key] = $this->modx->lexicon('googleanalytics.'.str_replace(' ', '_', strtolower($column)));
									
									break;
								case 'avgSessionDuration':
									$row[$key] = $this->timeFormat($column);
											
									break;
                                case 'goalStartsAll':
                                case 'goalCompletionsAll':
                                    $row[$key] = (int) $column;

                                    if (isset($totals['ga:'.$key])) {
                                        if (0 == $totals['ga:'.$key]) {
                                            $row[$key.'Percent'] = (int) 0;
                                        } else {
                                            $row[$key.'Percent'] = (int) round((100 / (int)$totals['ga:' . $key]) * (int)$column, 0);
                                        }
                                    }

                                    break;
								default:
								    if (is_numeric($column)) {
                                        $row[$key] = (int) $column;
                                    } else {
                                        $row[$key] = $column;
                                    }
									
									break;
							}
						}
						
						$output[] = $row;	
					}	
				}
				
				switch ($type) {
					case 'sources-summary':
						$output = $this->parseDataSources($output);
						
						break;
				}
			}
						
			return $output;
		}
		
		/**
		 * @access public.
		 * @param Array $data.
		 * @return Array.
		 */
		public function parseDataMeta($data) {
			$output = array();

			$newData = $data;
			$oldData = $this->getData('meta', array(
				'start-date'	=> date('Y-m-d', strtotime('-'.($this->getHistory() * 2).' days')),
				'end-date'		=> date('Y-m-d', strtotime('-'.$this->getHistory().' days'))
			));
			
			foreach($newData as $key => $value) {
				$data = false;
				
				switch (substr($key, 3, strlen($key))) {
					case 'visits':
						$data = array(
							'name' 		=> $this->modx->lexicon('googleanalytics.visits')
						);
						
						break;
					case 'visitors':
						$data = array(
							'name' 		=> $this->modx->lexicon('googleanalytics.visitors')
						);
					
						break;
					case 'pageviews':
						$data = array(
							'name' 		=> $this->modx->lexicon('googleanalytics.pageviews')
						);
						
						break;
					case 'uniquePageviews':
						$data = array(
							'name' 		=> $this->modx->lexicon('googleanalytics.pageviews_unique')
						);
						
						break;
					case 'percentNewVisits':
						$data = array(
							'name' 		=> $this->modx->lexicon('googleanalytics.visits_new'),
							'value'		=> round($value, 0).'%'
						);

						break;
					case 'exitRate':
						$data = array(
							'name' 		=> $this->modx->lexicon('googleanalytics.exitrate'),
							'value'		=> round($value, 0).'%'
						);
						
						break;
					case 'avgSessionDuration':
						$data = array(
							'name' 		=> $this->modx->lexicon('googleanalytics.visitors_time'),
							'value'		=> $this->timeFormat($value)
						);
						
						break;
					case 'visitBounceRate':
						$data = array(
							'name' 		=> $this->modx->lexicon('googleanalytics.bouncerate'),
							'value'		=> round($value, 0).'%'
						);

						break;
				}
				
				if (false !== $data) {
					$output[] = array_merge(array(
						'progress'		=> round(((int) $value - (int) $oldData[$key]) / ((int) $value / 100), 1),
						'value'			=> round($value, 0)	
					), $data);
				}
			}

			if (!empty($output)) {
				return array_chunk($output, 4);
			}
			
			return array();
		}
		
		/**
		 * @access public.
		 * @param Array $data.
		 * @return Array.
		 */
		public function parseDataSources($data) {
			$output = array(
				'search'	=> array(
					'name' 		=> $this->modx->lexicon('googleanalytics.source_search'),
					'visits' 	=> 0
				),
				'social'	=> array(
					'name' 		=> $this->modx->lexicon('googleanalytics.source_socialmedia'),
					'visits' 	=> 0
				),
				'reference'	=> array(
					'name' 		=> $this->modx->lexicon('googleanalytics.source_reference'),
					'visits' 	=> 0
				),
				'direct'	=> array(
					'name' 		=> $this->modx->lexicon('googleanalytics.source_direct'),
					'visits' 	=> 0
				)
			);
			
			foreach ($data as $value) {
				if (false !== strstr($value['source'], 'search')) {
					$output['search']['visits'] += (int) $value['visits'];
				} else if (false !== strstr($value['source'], 'google')) {
					$output['search']['visits'] += (int) $value['visits'];
				} else if (false !== strstr($value['source'], 'yahoo')) {
					$output['search']['visits'] += (int) $value['visits'];
				} else if (false !== strstr($value['source'], 'bing')) {
					$output['search']['visits'] += (int) $value['visits'];
				} else if (false !== strstr($value['source'], 'facebook')) {
					$output['social']['visits'] += (int) $value['visits'];
				} else if (false !== strstr($value['source'], 'twitter')) {
					$output['social']['visits'] += (int) $value['visits'];
				} else if (false !== strstr($value['source'], 'linkedin')) {
					$output['social']['visits'] += (int) $value['visits'];
				} else if (false !== strstr($value['source'], 'pinterest')) {
					$output['social']['visits'] += (int) $value['visits'];
				} else if (false !== strstr($value['source'], 'plus')) {
					$output['social']['visits'] += (int) $value['visits'];
				} else if (false !== strstr($value['source'], 'instagram')) {
					$output['social']['visits'] += (int) $value['visits'];
				} else if (false !== strstr($value['source'], 'direct')) {
					$output['direct']['visits'] += (int) $value['visits'];
				} else {
					$output['reference']['visits'] += (int) $value['visits'];
				}
			}
			
			return array_values($output);
		}
		
		/**
	     * @access private.
	     * @param Integer $timestamp
	     * @return String
	     */
	    private function timeFormat($timestamp) {
        	$minutes = floor($timestamp / 60);

        	if ($minutes < 10) {
        		$minutes = '0'.$minutes;
        	}

			$seconds = round(($timestamp / 60 - $minutes) * 60);
			
			if ($seconds < 10) {
				$seconds = '0'.$seconds;
			}

			return $minutes.':'.$seconds;
		}
		
		/**
		 * @access public.
		 * @param String $url.
		 * @param Array $paramters.
		 * @param String $method.
		 * @param Array $options.
		 * @return Array|Boolean.
		 */
		public function request($url, $parameters = array(), $method = 'GET', $options = array()) {
	    	if (false === strrpos($url, 'https://') && false === strrpos($url, 'http://')) {
				$url = rtrim(GoogleAnalyticsRequest::API_URL, '/').'/'.rtrim($url, '/').'/';
			}
			
			$parameters = array_merge($parameters, array(
	        	'access_token' => $this->getAccessToken(),
        	));
        	
			return $this->requestApi($url, $parameters, $method, $options);
	    }
		
		/**
	     * @access public.
	     * @param Array $options.
	     * @return Array|Boolean.
	     */
		public function requestApi($url, $parameters = array(), $method = 'GET', $options = array()) {
	        $options = $options + array(
	            CURLOPT_HEADER 			=> false,
	            CURLOPT_USERAGENT 		=> $this->getUserAgent(), 
	            CURLOPT_RETURNTRANSFER 	=> true,
	            CURLOPT_TIMEOUT 		=> 10
	        );

	        switch (strtoupper($method)) {
		        case 'POST':
		        	$options = array(
			        	CURLOPT_URL 		=> $url,
			        	CURLOPT_POSTFIELDS	=> http_build_query($parameters)
		        	) + $options;
		        	
		        	break;
		        default:
		        	$options = array(
			        	CURLOPT_URL 		=> $url.'?'.http_build_query($parameters)
		        	) + $options;
		        	
		        	break;
	        }

	        $curl = curl_init();
	        
	        curl_setopt_array($curl, $options);

	        $response 	= curl_exec($curl);
	        $info		= curl_getinfo($curl);

	        if (!isset($info['http_code']) || '200' != $info['http_code']) {
				return false;
			}
	        
	        curl_close($curl);
	        
	        return $this->modx->fromJSON($response);
	    }
	}

?>