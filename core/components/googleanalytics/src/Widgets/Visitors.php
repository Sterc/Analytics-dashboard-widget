<?php
namespace Sterc\GoogleAnalytics\Widgets;

use MODX\Revolution\modDashboardFileWidget;
use Sterc\GoogleAnalytics\GoogleAnalytics;

class Visitors extends modDashboardFileWidget
{
    /**
     * @access public.
     * @var String.
     */
    public $cssBlockClass = 'dashboard-google-analytics';

    /**
     * @var GoogleAnalytics
     */
    protected $googleanalytics;

    /**
     * @access public.
     * @return String.
     */
    public function render()
    {
        if ($this->modx->hasPermission('googleanalytics')) {
            $this->googleanalytics = new GoogleAnalytics($this->modx);

            $profile = $this->googleanalytics->getAuthorizedProfile();
            if ($profile) {
                $this->modx->regClientCSS($this->googleanalytics->config['css_url'] . 'mgr/googleanalytics.css');

                $this->modx->regClientStartupScript($this->googleanalytics->config['js_url'] . 'mgr/googleanalytics.js');

                $this->modx->regClientStartupHTMLBlock('<script type="text/javascript">
                    Ext.onReady(function() {
                        GoogleAnalytics.config = ' . $this->modx->toJSON(array_merge($this->googleanalytics->config, [
                            'authorized'            => $this->googleanalytics->isAuthorized(),
                            'authorized_profile'    => $this->googleanalytics->getAuthorizedProfile()
                        ])) . ';
                    });
                </script>');

                $this->modx->regClientStartupScript($this->googleanalytics->config['js_url'].'mgr/libs/jquery.min.js');
                $this->modx->regClientStartupScript($this->googleanalytics->config['js_url'].'mgr/libs/highcharts.js');
                $this->modx->regClientStartupScript($this->googleanalytics->config['js_url'].'mgr/widgets/home.charts.js');
                $this->modx->regClientStartupScript($this->googleanalytics->config['js_url'].'mgr/sections/visitors.widget.js');

                if (is_array($this->googleanalytics->config['lexicons'])) {
                    foreach ($this->googleanalytics->config['lexicons'] as $lexicon) {
                        $this->modx->controller->addLexiconTopic($lexicon);
                    }
                } else {
                    $this->modx->controller->addLexiconTopic($this->googleanalytics->config['lexicons']);
                }

                $this->widget->set('name', $this->modx->lexicon('googleanalytics.widget_visitors_title', ['property' => $profile['property_id']]));

                return $this->modx->smarty->fetch($this->googleanalytics->config['templates_path'] . 'widgets/visitors.tpl');
            }
        }
    }
}

return '\\Sterc\\GoogleAnalytics\\Widgets\\Visitors';
