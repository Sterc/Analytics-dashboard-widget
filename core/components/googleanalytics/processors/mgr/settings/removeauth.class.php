<?php

/**
 * Google Analytics
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class GoogleAnalyticsSettingsRemoveAuthProcessor extends modObjectProcessor
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
     * @return Array.
     */
    public function process()
    {
        foreach (['account', 'refresh_token'] as $key) {
            $setting = $this->modx->getObject('modSystemSetting', [
                'key' => 'googleanalytics.' . $key
            ]);

            if ($setting) {
                $setting->set('value', '');

                $setting->save();
            }
        }

        $this->modx->getCacheManager()->delete('googleanalytics');

        $this->modx->getCacheManager()->delete('config', [
            xPDO::OPT_CACHE_KEY => 'system_settings'
        ]);

        return $this->success();
    }
}

return 'GoogleAnalyticsSettingsRemoveAuthProcessor';
