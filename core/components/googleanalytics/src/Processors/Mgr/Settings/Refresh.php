<?php
namespace Sterc\GoogleAnalytics\Processors\Mgr\Settings;

use MODX\Revolution\Processors\Processor;

class Refresh extends Processor
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
    public function process()
    {
        $this->modx->getCacheManager()->delete('googleanalytics');

        return $this->success();
    }
}
