<?php
namespace Sterc\GoogleAnalytics\Controllers;

use Sterc\GoogleAnalytics\Controllers\Base;


class Home extends Base
{
    /**
     * @access public.
     */
    public function loadCustomCssJs()
    {
        if ($this->googleanalytics->getAuthorizedProfile()) {
            $this->addJavascript($this->googleanalytics->config['js_url'] . 'mgr/libs/jquery.min.js');
            $this->addJavascript($this->googleanalytics->config['js_url'] . 'mgr/libs/highcharts.js');

            $this->addJavascript($this->googleanalytics->config['js_url'] . 'mgr/widgets/home.panel.js');
            $this->addJavascript($this->googleanalytics->config['js_url'] . 'mgr/widgets/home.grid.js');
            $this->addJavascript($this->googleanalytics->config['js_url'] . 'mgr/widgets/home.charts.js');
        } else {
            $this->addJavascript($this->googleanalytics->config['js_url'] . 'mgr/widgets/access.panel.js');
        }

        $this->addLastJavascript($this->googleanalytics->config['js_url'] . 'mgr/sections/home.js');
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
        if ($this->googleanalytics->getAuthorizedProfile()) {
            return $this->googleanalytics->config['templates_path'] . 'home.tpl';
        }

        return $this->googleanalytics->config['templates_path'] . 'access.tpl';
    }
}
