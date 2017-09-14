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
	 * Coaching is distributed in the hope that it will be useful, but WITHOUT ANY
	 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
	 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License along with
	 * Google Analytics; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
	 * Suite 330, Boston, MA 02111-1307 USA
	 */

	class GoogleAnalyticsSettingsRevokeAuthProcessor extends modProcessor {
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
         * @param String $code.
		 * @return Array|Boolean.
		 */
		public function process() {
            $this->modx->getCacheManager()->clearCache();

            if (null !== ($object = $this->modx->getObject('modSystemSetting', 'googleanalytics.account'))) {
                $object->fromArray(array(
                    'value' => ''
                ));

                $object->save();
            }

            if (null !== ($object = $this->modx->getObject('modSystemSetting', 'googleanalytics.refresh_token'))) {
                $object->fromArray(array(
                    'value' => ''
                ));

                if ($object->save()) {
                    return $this->success();
                }
            }

            return $this->failure();
		}
	}

	return 'GoogleAnalyticsSettingsRevokeAuthProcessor';

?>