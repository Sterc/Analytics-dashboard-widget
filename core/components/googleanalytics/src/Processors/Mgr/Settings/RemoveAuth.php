<?php
namespace Sterc\GoogleAnalytics\Processors\Mgr\Settings;

use MODX\Revolution\Processors\Processor;
use Sterc\GoogleAnalytics\GoogleAnalytics;
use MODX\Revolution\modSystemSetting;
use xPDO;

class RemoveAuth extends Processor
{
    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['googleanalytics:default'];

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

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Array.
     */
    public function process()
    {
        foreach (['account', 'refresh_token'] as $key) {
            $setting = $this->modx->getObject(modSystemSetting::class, ['key' => 'googleanalytics.' . $key]);
            if ($setting) {
                $setting->set('value', '');

                $setting->save();
            }
        }

        $this->modx->getCacheManager()->delete('googleanalytics');
        $this->modx->getCacheManager()->delete('config', [xPDO::OPT_CACHE_KEY => 'system_settings']);

        return $this->success();
    }
}
