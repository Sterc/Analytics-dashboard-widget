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

	class GoogleAnalyticsSettingsAuthProcessor extends modProcessor {
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

		    $code = $this->getProperty('code');

		    if (false !== ($tokens = $this->googleanalytics->getAuthorizeTokens($code))) {
                if (isset($tokens['access_token'])) {
                    if (null !== ($object = $this->modx->getObject('modSystemSetting', 'googleanalytics.refresh_token'))) {
                        $object->fromArray(array(
                            'value' => $tokens['refresh_token']
                        ));

                        if ($object->save()) {
                            $this->modx->getCacheManager()->set('access_token', $token['access_token'], $tokens['expires_in'], array(
                                xPDO::OPT_CACHE_KEY => 'googleanalytics'
                            ));

                            return $this->success();
                        } else {
                            $this->addFieldError('code', $this->modx->lexicon('googleanalytics.auth_error_save'));
                        }
                    }
                } else {
                    $this->addFieldError('code', $this->modx->lexicon('googleanalytics.auth_error'));
                }
            } else {
                $this->addFieldError('code', $this->modx->lexicon('googleanalytics.auth_error'));
            }

		    return $this->failure();
		}
	}

	return 'GoogleAnalyticsSettingsAuthProcessor';

?>