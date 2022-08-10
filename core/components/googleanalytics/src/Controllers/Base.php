<?php
namespace Sterc\GoogleAnalytics\Controllers;

use MODX\Revolution\modExtraManagerController;
use Sterc\GoogleAnalytics\GoogleAnalytics;

class Base extends modExtraManagerController
{
    /**
     * @var GoogleAnalytics
     */
    protected $googleanalytics;

    /**
     * @access public.
     * @return Mixed.
     */
    public function initialize()
    {
        $this->googleanalytics = new GoogleAnalytics($this->modx);

        $this->addCss($this->googleanalytics->config['css_url'] . 'mgr/googleanalytics.css');
        $this->addJavascript($this->googleanalytics->config['js_url'] . 'mgr/googleanalytics.js');
        $this->addJavascript($this->googleanalytics->config['js_url'] . 'mgr/extras/extras.js');

        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                MODx.config.help_url = "' . $this->googleanalytics->getHelpUrl() . '";

                GoogleAnalytics.config = ' . $this->modx->toJSON(array_merge($this->googleanalytics->config, [
                    'authorized'            => $this->googleanalytics->isAuthorized(),
                    'authorized_profile'    => $this->googleanalytics->getAuthorizedProfile(),
                    'branding_url'          => $this->googleanalytics->getBrandingUrl(),
                    'branding_url_help'     => $this->googleanalytics->getHelpUrl(),
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
        return $this->googleanalytics->config['lexicons'];
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
        $date2  = strtotime('-' . $this->googleanalytics->getOption('history') . ' days', $date1);

        return [
            'date_1'            => date('Y-m-d', $date2),
            'date_1_formatted'  => date($this->modx->getOption('manager_date_format'), $date2),
            'date_2'            => date('Y-m-d', $date1),
            'date_2_formatted'  => date($this->modx->getOption('manager_date_format'), $date1),
        ];
    }
}

