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
	 
	require_once dirname(__FILE__).'/googleanalyticsrequest.class.php';

	class GoogleAnalytics {
		/**
		 * @access public.
		 * @var Object.
		 */
		public $modx;
		
		/**
		 * @access public.
		 * @var Array.
		 */
		public $config = array();
		
		/**
		 * @acces public.
		 * @var Null|Object.
		 */
		public $request = null;

		/**
		 * @access public.
		 * @param Object $modx.
		 * @param Array $config.
		 */
		public function __construct(modX &$modx, array $config = array()) {
			$this->modx =& $modx;

			$corePath 		= $this->modx->getOption('googleanalytics.core_path', $config, $this->modx->getOption('core_path').'components/googleanalytics/');
			$assetsUrl 		= $this->modx->getOption('googleanalytics.assets_url', $config, $this->modx->getOption('assets_url').'components/googleanalytics/');
			$assetsPath 	= $this->modx->getOption('googleanalytics.assets_path', $config, $this->modx->getOption('assets_path').'components/googleanalytics/');
		
			$this->config = array_merge(array(
				'namespace'				=> $this->modx->getOption('namespace', $config, 'googleanalytics'),
				'lexicons'				=> array('googleanalytics:default'),
				'base_path'				=> $corePath,
				'core_path' 			=> $corePath,
				'model_path' 			=> $corePath.'model/',
				'processors_path' 		=> $corePath.'processors/',
				'elements_path' 		=> $corePath.'elements/',
				'chunks_path' 			=> $corePath.'elements/chunks/',
				'cronjobs_path' 		=> $corePath.'elements/cronjobs/',
				'plugins_path' 			=> $corePath.'elements/plugins/',
				'snippets_path' 		=> $corePath.'elements/snippets/',
				'templates_path' 		=> $corePath.'templates/',
				'assets_path' 			=> $assetsPath,
				'js_url' 				=> $assetsUrl.'js/',
				'css_url' 				=> $assetsUrl.'css/',
				'assets_url' 			=> $assetsUrl,
				'connector_url'			=> $assetsUrl.'connector.php',
				'version'				=> '2.0.0',
				'branding_url'			=> $this->modx->getOption('googleanalytics.branding_url', null, ''),
				'branding_help_url'		=> $this->modx->getOption('googleanalytics.branding_url_help', null, ''),
				'has_permission'		=> $this->hasPermission(),
                'authorize_url'         => $this->getAuthorizeUrl(),
				'google_analytics_url'	=> 'http://www.google.nl/analytics/',
				'history'				=> (int) $this->modx->getOption('googleanalytics.history', null, 7),
                'panels'				=> explode(',', $this->modx->getOption('googleanalytics.panels', null, ''))
			), $config);

			$this->modx->addPackage('googleanalytics', $this->config['model_path']);
			
			if (is_array($this->config['lexicons'])) {
				foreach ($this->config['lexicons'] as $lexicon) {
					$this->modx->lexicon->load($lexicon);
				}
			} else {
				$this->modx->lexicon->load($this->config['lexicons']);
			}
		}
		
		/**
		 * @access public.
		 * @return String|Boolean.
		 */
		public function getHelpUrl() {
		    if (!empty($this->config['branding_help_url'])) {
                return $this->config['branding_help_url'].'?v=' . $this->config['version'];
            }

            return false;
		}

        /**
         * @access public.
         * @return String|Boolean.
         */
        public function getBrandingUrl() {
            if (!empty($this->config['branding_url'])) {
                return $this->config['branding_url'];
            }

            return false;
        }
		
		/**
		 * @access public.
		 * @return Boolean.
		 */
		public function hasPermission() {
			return $this->modx->hasPermission('googleanalytics_settings');
		}
		
		/**
		 * @access public.
		 * @return Object.
		 */
		public function getRequest() {
			if (null === $this->request) {
				$this->request = new GoogleAnalyticsRequest($this->modx, $this);
			}
			
			return $this->request;
		}

        /**
         * @access public.
         * @return String.
         */
        public function getAuthorizeUrl() {
            return $this->getRequest()->getAuthorizeUrl();
        }

        /**
         * @access public.
         * @param String $code.
         * @return Array|Boolean.
         */
        public function getAuthorizeTokens($code) {
            return $this->getRequest()->getAuthorizeTokens($code);
        }
		
		/**
		 * @access public.
		 * @return Boolean.
		 */
		public function isAuthorized() {
			return $this->getRequest()->isAuthorized();
		}
		
		/**
		 * @access public.
		 * @return Array|Boolean.
		 */
		public function getAuthorizedProfile() {
			if ($this->isAuthorized()) {
				$account = $this->modx->getOption('googleanalytics.account');

				if (null !== ($account = $this->modx->fromJSON($account))) {
					$parameters = $this->modx->request->getParameters();

					if (isset($parameters['profile'])) {
						$account['profile'] = $parameters['profile'];  
					}
					
					$parameters = $this->modx->request->getParameters(array(), 'POST');
					
					if (isset($parameters['profile'])) {
						$account['profile'] = $parameters['profile'];  
					}

					if (isset($account['account'], $account['property'], $account['profile'])) {
						if (false !== ($data = $this->getRequest()->getAccountProfile($account['account'], $account['property'], $account['profile']))) {
						    return array(
								'id'				=> $data['id'],
								'account_id'		=> $data['accountId'],
								'property_id'		=> $data['webPropertyId'],
								'property_id_int'	=> $data['internalWebPropertyId'],
								'name'				=> $data['name'],
								'url'				=> $data['websiteUrl'],
								'timezone'			=> $data['timezone'],
								'editedon'			=> date('Y-m-d H:i:s', strtotime($data['updated'])),
								'created'			=> date('Y-m-d H:i:s', strtotime($data['created']))
							);
						}
					}
				}
			}
			
			return false;
		}
	}

?>