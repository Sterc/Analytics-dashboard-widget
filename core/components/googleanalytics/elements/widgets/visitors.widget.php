<?php

use MODX\Revolution\modDashboardFileWidget;

/**
 * Google Analytics
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class GoogleAnalyticsVisitorsDashboardWidget extends modDashboardFileWidget
{
    /**
     * @access public.
     * @var String.
     */
    public $cssBlockClass = 'dashboard-google-analytics';

    /**
     * @access public.
     * @return String.
     */
    public function render()
    {
        if ($this->modx->hasPermission('googleanalytics')) {
            $this->modx->getService('googleanalytics', 'GoogleAnalytics', $this->modx->getOption('googleanalytics.core_path', null, $this->modx->getOption('core_path') . 'components/googleanalytics/') . 'model/googleanalytics/');

            $profile = $this->modx->googleanalytics->getAuthorizedProfile();

            if ($profile) {
                $this->modx->regClientCSS($this->modx->googleanalytics->config['css_url'] . 'mgr/googleanalytics.css');

                $this->modx->regClientStartupScript($this->modx->googleanalytics->config['js_url'] . 'mgr/googleanalytics.js');

                $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
                    Ext.onReady(function() {
                        GoogleAnalytics.config = ' . $this->modx->toJSON(array_merge($this->modx->googleanalytics->config, [
                            'authorized'            => $this->modx->googleanalytics->isAuthorized(),
                            'authorized_profile'    => $this->modx->googleanalytics->getAuthorizedProfile()
                        ])) . ';
                    });
                </script>');

                $this->modx->regClientStartupScript($this->modx->googleanalytics->config['js_url'].'mgr/libs/jquery.min.js');
                $this->modx->regClientStartupScript($this->modx->googleanalytics->config['js_url'].'mgr/libs/highcharts.js');

                $this->modx->regClientStartupScript($this->modx->googleanalytics->config['js_url'].'mgr/widgets/home.charts.js');

                $this->modx->regClientStartupScript($this->modx->googleanalytics->config['js_url'].'mgr/sections/visitors.widget.js');

                if (is_array($this->modx->googleanalytics->config['lexicons'])) {
                    foreach ($this->modx->googleanalytics->config['lexicons'] as $lexicon) {
                        $this->modx->controller->addLexiconTopic($lexicon);
                    }
                } else {
                    $this->modx->controller->addLexiconTopic($this->modx->googleanalytics->config['lexicons']);
                }

                $this->widget->set('name', $this->modx->lexicon('googleanalytics.widget_visitors_title', [
                    'property' => $profile['property_id']
                ]));

                return $this->modx->smarty->fetch($this->modx->googleanalytics->config['templates_path'] . 'widgets/visitors.tpl');
            }
        }
    }
}

return 'GoogleAnalyticsVisitorsDashboardWidget';
