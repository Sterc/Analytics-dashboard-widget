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

	class GoogleAnalyticsHomeManagerController extends GoogleAnalyticsManagerController {
		/**
		 * @access public.
		 */
		public function loadCustomCssJs() {
			$this->addCss($this->googleanalytics->config['css_url'].'mgr/googleanalytics.css');
			
			if ($this->googleanalytics->getAuthorizedProfile()) {
                $this->addJavascript($this->googleanalytics->config['js_url'].'mgr/libs/jquery.min.js');
                $this->addJavascript($this->googleanalytics->config['js_url'].'mgr/libs/highcharts.js');

				$this->addJavascript($this->googleanalytics->config['js_url'].'mgr/widgets/home.panel.js');
				$this->addJavascript($this->googleanalytics->config['js_url'].'mgr/widgets/home.grid.js');
                $this->addJavascript($this->googleanalytics->config['js_url'].'mgr/widgets/home.charts.js');
			} else {
				$this->addJavascript($this->googleanalytics->config['js_url'].'mgr/widgets/access.panel.js');
			}

			$this->addLastJavascript($this->googleanalytics->config['js_url'].'mgr/sections/home.js');
		}
		
		/**
		 * @access public.
		 * @return String.
		 */
		public function getPageTitle() {
			return $this->modx->lexicon('googleanalytics');
		}

		/**
		* @access public.
		* @return String.
		*/
		public function getTemplateFile() {
			if ($this->googleanalytics->getAuthorizedProfile()) {
				return $this->googleanalytics->config['templates_path'].'home.tpl';
			} else {
				return $this->googleanalytics->config['templates_path'].'access.tpl';
			}
		}
	}

?>