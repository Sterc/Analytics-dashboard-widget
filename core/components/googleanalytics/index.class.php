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

	abstract class GoogleAnalyticsManagerController extends modExtraManagerController {
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

			$this->addJavascript($this->googleanalytics->config['js_url'].'mgr/googleanalytics.js');
			
			$this->addHtml('<script type="text/javascript">
				Ext.onReady(function() {
					MODx.config.help_url = "'.$this->googleanalytics->getHelpUrl().'";
					
					GoogleAnalytics.config = '.$this->modx->toJSON(array_merge($this->googleanalytics->config, array(
                        'authorized'			=> $this->googleanalytics->isAuthorized(),
                        'authorized_profile'	=> $this->googleanalytics->getAuthorizedProfile(),
                        'branding_url'          => $this->googleanalytics->getBrandingUrl(),
                        'branding_url_help'     => $this->googleanalytics->getHelpUrl()
                    ))).';
				});
			</script>');
			
			return parent::initialize();
		}
		
		/**
		 * @access public.
		 * @return Array.
		 */
		public function getLanguageTopics() {
			return $this->googleanalytics->config['lexicons'];
		}
		
		/**
		 * @access public.
		 * @returns Boolean.
		 */	    
		public function checkPermissions() {
			return $this->modx->hasPermission('googleanalytics');
		}
	}
		
	class IndexManagerController extends GoogleAnalyticsManagerController {
		/**
		 * @access public.
		 * @return String.
		 */
		public static function getDefaultController() {
			return 'home';
		}
	}

?>