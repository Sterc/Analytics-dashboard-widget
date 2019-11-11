<?php

/**
 * Google Analytics
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class GoogleAnalyticsSettingsCreateAuthProcessor extends modObjectProcessor
{
    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['googleanalytics:default'];

    /**
     * @access public.
     * @return Mixed.
     */
    public function initialize()
    {
        $this->modx->getService('googleanalytics', 'GoogleAnalytics', $this->modx->getOption('googleanalytics.core_path', null, $this->modx->getOption('core_path') . 'components/googleanalytics/') . 'model/googleanalytics/');

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Array|Boolean.
     */
    public function process()
    {
        $this->modx->getCacheManager()->refresh();

        $data = $this->modx->googleanalytics->getAuthorizeTokens($this->getProperty('code'));

        if ((int) $data['code'] === 200) {
            if (isset($data['data']['access_token'])) {
                $setting = $this->modx->getObject('modSystemSetting', [
                    'key' => 'googleanalytics.refresh_token'
                ]);

                if ($setting) {
                    $setting->fromArray([
                        'value' => $data['data']['refresh_token']
                    ]);

                    if ($setting->save()) {
                        $this->modx->getCacheManager()->set('googleanalytics/access_token', $data['data']['access_token'], $data['data']['expires_in']);

                        return $this->success();
                    }

                    $this->addFieldError('code', $this->modx->lexicon('googleanalytics.auth_error_save'));
                }
            } else {
                $this->addFieldError('code', $this->modx->lexicon('googleanalytics.auth_error'));
            }
        } else {
            $this->addFieldError('code', $this->modx->lexicon('googleanalytics.auth_error'));
        }

        return $this->failure($data['data']['message']);
    }
}

return 'GoogleAnalyticsSettingsCreateAuthProcessor';
