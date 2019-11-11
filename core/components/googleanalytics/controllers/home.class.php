<?php

/**
 * Google Analytics
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

require_once dirname(__DIR__) . '/index.class.php';

class GoogleAnalyticsHomeManagerController extends GoogleAnalyticsManagerController
{
    /**
     * @access public.
     */
    public function loadCustomCssJs()
    {
        if ($this->modx->googleanalytics->getAuthorizedProfile()) {
            $this->addJavascript($this->modx->googleanalytics->config['js_url'] . 'mgr/libs/jquery.min.js');
            $this->addJavascript($this->modx->googleanalytics->config['js_url'] . 'mgr/libs/highcharts.js');

            $this->addJavascript($this->modx->googleanalytics->config['js_url'] . 'mgr/widgets/home.panel.js');
            $this->addJavascript($this->modx->googleanalytics->config['js_url'] . 'mgr/widgets/home.grid.js');
            $this->addJavascript($this->modx->googleanalytics->config['js_url'] . 'mgr/widgets/home.charts.js');
        } else {
            $this->addJavascript($this->modx->googleanalytics->config['js_url'] . 'mgr/widgets/access.panel.js');
        }

        $this->addLastJavascript($this->modx->googleanalytics->config['js_url'] . 'mgr/sections/home.js');
    }

    /**
     * @access public.
     * @return String.
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('googleanalytics');
    }

    /**
     * @access public.
     * @return String.
     */
    public function getTemplateFile()
    {
        if ($this->modx->googleanalytics->getAuthorizedProfile()) {
            return $this->googleanalytics->config['templates_path'] . 'home.tpl';
        }

        return $this->modx->googleanalytics->config['templates_path'] . 'access.tpl';
    }
}
