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
	 
	class SocialMediaSourcesGetProfilesProcessor extends modProcessor {
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
			
			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @return Array.
		 */
		public function process() {
			$output = array();
			
			$account 	= $this->getProperty('account');
			$property	= $this->getProperty('property');
			$query 		= $this->getProperty('query');

			if (null !== ($data = $this->googleanalytics->getRequest()->getAccountProfiles($account, $property))) {
				if (isset($data['items'])) {
					foreach ($data['items'] as $key => $value) {
						$value = array(
							'id'	=> $value['id'],
							'name'	=> $value['name'],
							'url'	=> $value['websiteUrl']
						);
						
						if (!empty($query)) {
							if (false !== strstr(strtolower($value['id']), strtolower($query))) {
								$output[] = $value;
							} else if (false !== strstr(strtolower($value['name']), strtolower($query))) {
								$output[] = $value;
							} else if (false !== strstr(strtolower($value['url']), strtolower($query))) {
								$output[] = $value;
							}
						} else {
							$output[] = $value;
						}
					}
				}
			}
			
			$sort = array();
			
			foreach ($output as $key => $value) {
			    $sort[$key] = $value['name'];
			}
			
			array_multisort($sort, SORT_ASC, $output);
			
			return $this->outputArray($output);
		}
	}

	return 'SocialMediaSourcesGetProfilesProcessor';
	
?>