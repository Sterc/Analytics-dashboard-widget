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
	 
	class SocialMediaSourcesGetPropertiesProcessor extends modProcessor {
		/**
		 * @access public.
		 * @var Array.
		 */
		public $languageTopics = array('googleanalytics:default');
				
		/**
		 * @access public.
		 * @var String.
		 */
		public $objectType = 'googleanalytics.accounts';
		
		/**
		 * @access public.
		 * @var Object.
		 */
		public $googleanalytics;
		
		/**
		 * @access public.
		 * @return Mixed.
		 */
		public function initialize() {
			$this->googleanalytics = $this->modx->getService('googleanalytics', 'GoogleAnalytics', $this->modx->getOption('googleanalytics.core_path', null, $this->modx->getOption('core_path').'components/googleanalytics/').'model/googleanalytics/');
			
			$this->setDefaultProperties(array(
				'cache' 	=> true,
				'history'	=> $this->googleanalytics->config['history'],
				'limit'		=> 0
			));
			
			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @return Array.
		 */
		public function process() {
			$output 	= array();
			$type 		= $this->getProperty('data');
			
			if (null !== ($history = $this->getProperty('history'))) {
				$this->googleanalytics->getRequest()->setHistory($history);
			}
			
			if (!empty($type)) {
				if ((bool) $this->getProperty('cache') && 'realtime' != $type) {
					if (null !== ($data = $this->getCache())) {
						$output = $data;
					} else {
						if (null !== ($data = $this->googleanalytics->getRequest()->getData($type))) {
							$this->setCache($data);
							
							$output = $data;
						} 
					}
				} else {
					if (null !== ($data = $this->googleanalytics->getRequest()->getData($type))) {
						$this->setCache($data);
						
						$output = $data;
					} 
				}
			}
			
			if (0 != ($limit = $this->getProperty('limit'))) {
				$output = array_slice($output, 0, $limit);
			}
						
			return $this->outputArray($output);
		}

		/**
		 * @access public.
		 * @return Boolean.
		 */
		public function setCache($output) {			
			return $this->modx->cacheManager->set($this->getCacheOptions(xPDO::OPT_CACHE_KEY), $output, $this->getCacheOptions(xPDO::OPT_CACHE_EXPIRES));
		}
		
		/**
		 * @access public.
		 * @return Boolean.
		 */
		public function getCache() {
			if (null !== ($output = $this->modx->cacheManager->get($this->getCacheOptions(xPDO::OPT_CACHE_KEY)))) {
				return $output;
			}
			
			return null;
		}
		
		/**
		 * @access public.
		 * @param String $option.
		 * @return Array.
		 */
		public function getCacheOptions($option = null) {
		    if ($profile = $this->googleanalytics->getAuthorizedProfile()) {
                $options = array(
                    xPDO::OPT_CACHE_KEY 	=> 'googleanalytics/'.$profile['id'].'/'.$this->getProperty('data'),
                    xPDO::OPT_CACHE_HANDLER => $this->modx->getOption(xPDO::OPT_CACHE_HANDLER, null, 'xPDOFileCache'),
                    xPDO::OPT_CACHE_EXPIRES => 3600
                );

                if (isset($options[$option])) {
                    return $options[$option];
                }

                return $options;
            }

			return false;
		}
	}

	return 'SocialMediaSourcesGetPropertiesProcessor';
	
?>