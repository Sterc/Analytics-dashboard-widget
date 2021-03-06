<?php

/**
 * Google Analytics
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class GoogleAnalyticsSettingsSaveProcessor extends modObjectProcessor
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
     * @return Mixed.
     */
    public function process()
    {
        foreach (['history', 'cache_lifetime', 'panels'] as $key) {
            $setting = $this->modx->getObject('modSystemSetting', [
                'key' => 'googleanalytics.' . $key
            ]);

            if ($setting) {
                $value = $this->getProperty($key);

                if (is_array($value)) {
                    $setting->set('value', implode(',', $value));
                } else {
                    $setting->set('value', $value);
                }

                $setting->save();
            }
        }

        $setting = $this->modx->getObject('modSystemSetting', [
            'key' => 'googleanalytics.account'
        ]);

        if ($setting) {
            $setting->set('value', json_encode([
                'account'   => $this->getProperty('account'),
                'property'  => $this->getProperty('property'),
                'profile'   => $this->getProperty('profile')
            ]));

            $setting->save();
        }

        $this->modx->getCacheManager()->delete('googleanalytics');

        $this->modx->getCacheManager()->delete('config', [
            xPDO::OPT_CACHE_KEY => 'system_settings'
        ]);

        return $this->success();
    }
}

return 'GoogleAnalyticsSettingsSaveProcessor';
