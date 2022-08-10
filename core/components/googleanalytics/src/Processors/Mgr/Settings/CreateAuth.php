<?php
namespace Sterc\GoogleAnalytics\Processors\Mgr\Settings;

use MODX\Revolution\Processors\Processor;
use Sterc\GoogleAnalytics\GoogleAnalytics;

class CreateAuth extends Processor
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
     * @return Array|Boolean.
     */
    public function process()
    {
        $this->modx->getCacheManager()->refresh();

        $data = $this->googleanalytics->getAuthorizeTokens($this->getProperty('code'));
        if ((int) $data['code'] === 200) {
            if (isset($data['data']['access_token'])) {
                $setting = $this->modx->getObject('modSystemSetting', [
                    'key' => 'googleanalytics.refresh_token'
                ]);

                if ($setting) {
                    $setting->fromArray(['value' => $data['data']['refresh_token']]);

                    if ($setting->save()) {
                        $this->modx->getCacheManager()->set('googleanalytics/access_token', $data['data']['access_token'], $data['data']['expires_in']);

                        return $this->success();
                    }

                    $this->addFieldError('code', $this->modx->lexicon('googleanalytics.auth_error_save'));
                }
            } else {
                $this->addFieldError('code', $this->modx->lexicon('googleanalytics.auth_error'));
            }
        } else {
            $this->addFieldError('code', $this->modx->lexicon('googleanalytics.auth_error'));
        }

        return $this->failure($data['data']['message']);
    }
}
