<?php

/**
 * Google Analytics
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

abstract class GoogleAnalyticsManagerController extends modExtraManagerController
{
    /**
     * @access public.
     * @return Mixed.
     */
    public function initialize()
    {
        $this->modx->getService('googleanalytics', 'GoogleAnalytics', $this->modx->getOption('googleanalytics.core_path', null, $this->modx->getOption('core_path') . 'components/googleanalytics/') . 'model/googleanalytics/');

        $this->addCss($this->modx->googleanalytics->config['css_url'] . 'mgr/googleanalytics.css');

        $this->addJavascript($this->modx->googleanalytics->config['js_url'] . 'mgr/googleanalytics.js');

        $this->addJavascript($this->modx->googleanalytics->config['js_url'] . 'mgr/extras/extras.js');

        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                MODx.config.help_url = "' . $this->modx->googleanalytics->getHelpUrl() . '";
                
                GoogleAnalytics.config = ' . $this->modx->toJSON(array_merge($this->modx->googleanalytics->config, [
                    'authorized'            => $this->modx->googleanalytics->isAuthorized(),
                    'authorized_profile'    => $this->modx->googleanalytics->getAuthorizedProfile(),
                    'branding_url'          => $this->modx->googleanalytics->getBrandingUrl(),
                    'branding_url_help'     => $this->modx->googleanalytics->getHelpUrl(),
                    'dates'                 => $this->getDates()
                ])) . ';
            });
        </script>');

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getLanguageTopics()
    {
        return $this->modx->googleanalytics->config['lexicons'];
    }

    /**
     * @access public.
     * @return Boolean.
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('googleanalytics');
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getDates()
    {
        $date1  = time();
        $date2  = strtotime('-' . $this->modx->googleanalytics->getOption('history') . ' days', $date1);

        return [
            'date_1'            => date('Y-m-d', $date2),
            'date_1_formatted'  => date($this->modx->getOption('manager_date_format'), $date2),
            'date_2'            => date('Y-m-d', $date1),
            'date_2_formatted'  => date($this->modx->getOption('manager_date_format'), $date1),
        ];
    }
}

class IndexManagerController extends GoogleAnalyticsManagerController
{
    /**
     * @access public.
     * @return String.
     */
    public static function getDefaultController()
    {
        return 'home';
    }
}
