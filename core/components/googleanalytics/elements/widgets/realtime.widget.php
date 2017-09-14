<?php

	/**
	 * Google Analytics
	 *
	 * Copyright 2017 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
	 *
	 * This file is part of Google Analytics, a real estate property listings component
	 * for MODX Revolution.
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

	class modDashboardWidgetGoogleAnalyticsRealtime extends modDashboardFileWidget {
		/**
		 * @acces public.
		 * @var Object.
		 */
		public $googleanalytics;
		
		/**
		 * @acces public.
		 * @var String.
		 */
		public $cssBlockClass = 'dashboard-google-analytics';
		
		/**
		 * @acces public.
		 * @return String.
		 */
		public function render() {
			$this->googleanalytics = $this->modx->getService('googleanalytics', 'GoogleAnalytics', $this->modx->getOption('googleanalytics.core_path', null, $this->modx->getOption('core_path').'components/googleanalytics/').'model/googleanalytics/');
			
			if (false !== ($account = $this->googleanalytics->getAuthorizedProfile())) {
				$this->modx->regClientCSS($this->googleanalytics->config['css_url'].'mgr/googleanalytics.css');
				
				$this->modx->regClientStartupScript($this->googleanalytics->config['js_url'].'mgr/googleanalytics.js');
				
				$this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
					Ext.onReady(function() {
						GoogleAnalytics.config = '.$this->modx->toJSON(array_merge($this->googleanalytics->config, array(
                            'authorized'			=> $this->googleanalytics->isAuthorized(),
                            'authorized_profile'	=> $this->googleanalytics->getAuthorizedProfile()
                        ))).';
					});
				</script>');
				
				$this->modx->regClientStartupScript($this->googleanalytics->config['js_url'].'mgr/sections/realtime.widget.js');

				if (is_array($this->googleanalytics->config['lexicons'])) {
					foreach ($this->googleanalytics->config['lexicons'] as $lexicon) {
						$this->modx->controller->addLexiconTopic($lexicon);
					}
				} else {
					$this->modx->controller->addLexiconTopic($this->googleanalytics->config['lexicons']);
				}

				$this->widget->name = $this->modx->lexicon('googleanalytics.widget_visitors_title', array(
					'property' => $account['property_id']
				));
				
				return $this->modx->smarty->fetch($this->googleanalytics->config['templates_path'].'widget.realtime.tpl');
			}
	    }
	}
	
	return 'modDashboardWidgetGoogleAnalyticsRealtime';
	
?>