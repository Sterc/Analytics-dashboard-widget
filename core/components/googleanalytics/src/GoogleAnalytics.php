<?php
namespace Sterc\GoogleAnalytics;

use MODX\Revolution\modX;
use Sterc\GoogleAnalytics\Services\Google;

class GoogleAnalytics
{
    /**
     * @access public.
     * @var modX.
     */
    public $modx;

    /**
     * @access public.
     * @var Array.
     */
    public $config = [];

    /**
     * @access public.
     * @var Object.
     */
    public $request = null;

    /**
     * @access public.
     * @param modX $modx.
     * @param Array $config.
     */
    public function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;

        $corePath   = $this->modx->getOption('googleanalytics.core_path', $config, $this->modx->getOption('core_path') . 'components/googleanalytics/');
        $assetsUrl  = $this->modx->getOption('googleanalytics.assets_url', $config, $this->modx->getOption('assets_url') . 'components/googleanalytics/');
        $assetsPath = $this->modx->getOption('googleanalytics.assets_path', $config, $this->modx->getOption('assets_path') . 'components/googleanalytics/');

        $this->config = array_merge([
            'namespace'             => 'googleanalytics',
            'lexicons'              => ['googleanalytics:default'],
            'base_path'             => $corePath,
            'core_path'             => $corePath,
            'processors_path'       => $corePath . 'processors/',
            'elements_path'         => $corePath . 'elements/',
            'chunks_path'           => $corePath . 'elements/chunks/',
            'plugins_path'          => $corePath . 'elements/plugins/',
            'snippets_path'         => $corePath . 'elements/snippets/',
            'widgets_path'          => $corePath . 'elements/widgets/',
            'templates_path'        => $corePath . 'templates/',
            'assets_path'           => $assetsPath,
            'js_url'                => $assetsUrl . 'js/',
            'css_url'               => $assetsUrl . 'css/',
            'assets_url'            => $assetsUrl,
            'connector_url'         => $assetsUrl . 'connector.php',
            'version'               => '3.0.2',
            'branding_url'          => $this->modx->getOption('googleanalytics.branding_url', null, ''),
            'branding_help_url'     => $this->modx->getOption('googleanalytics.branding_url_help', null, ''),
            'permissions'           => [
                'admin'                 => $this->modx->hasPermission('googleanalytics_admin'),
            ],
            'authorize_url'         => $this->getAuthorizeUrl(),
            'history'               => (int) $this->modx->getOption('googleanalytics.history', null, 14),
            'cache_lifetime'        => (int) $this->modx->getOption('googleanalytics.cache_lifetime', null, 1800),
            'panels'                => explode(',', $this->modx->getOption('googleanalytics.panels', null, '')),
            'google_analytics_url'  => 'http://www.google.nl/analytics/',
        ], $config);

        if (is_array($this->config['lexicons'])) {
            foreach ($this->config['lexicons'] as $lexicon) {
                $this->modx->lexicon->load($lexicon);
            }
        } else {
            $this->modx->lexicon->load($this->config['lexicons']);
        }
    }

    /**
     * @access public.
     * @return String|Boolean.
     */
    public function getHelpUrl()
    {
        $url = $this->getOption('branding_url_help');

        if (!empty($url)) {
            return $url . '?v=' . $this->config['version'];
        }

        return false;
    }

    /**
     * @access public.
     * @return String|Boolean.
     */
    public function getBrandingUrl()
    {
        $url = $this->getOption('branding_url');

        if (!empty($url)) {
            return $url;
        }

        return false;
    }

    /**
     * @access public.
     * @param String $key.
     * @param Array $options.
     * @param Mixed $default.
     * @return Mixed.
     */
    public function getOption($key, array $options = [], $default = null)
    {
        if (isset($options[$key])) {
            return $options[$key];
        }

        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return $this->modx->getOption($this->config['namespace'] . '.' . $key, $options, $default);
    }

    /**
     * @access public.
     * @return Object.
     */
    public function getRequest()
    {
        if ($this->request === null) {
            $this->request = new Google($this->modx, $this);
        }

        return $this->request;
    }

    /**
     * @access public.
     * @return String.
     */
    public function getAuthorizeUrl()
    {
        return $this->getRequest()->getAuthorizeUrl();
    }

    /**
     * @access public.
     * @param String $code.
     * @return Array.
     */
    public function getAuthorizeTokens($code)
    {
        return $this->getRequest()->getAuthorizeTokens($code);
    }

    /**
     * @access public.
     * @return Array.
     */
    public function isAuthorized()
    {
        return $this->getRequest()->isAuthorized();
    }

    /**
     * @access public.
     * @return Array|Boolean.
     */
    public function getAuthorizedProfile()
    {
        if ($this->isAuthorized()) {
            $account = json_decode($this->modx->getOption('googleanalytics.account', null, '{}'), true);

            if ($account) {
                if (isset($_GET['profile'])) {
                    $account['profile'] = $_GET['profile'];
                }

                if (isset($account['account'], $account['property'], $account['profile'])) {
                    $data = $this->modx->getCacheManager()->get('googleanalytics/' . $account['profile']);

                    if ($data) {
                        return $data;
                    }

                    $data = $this->getRequest()->getAccountProfile($account['account'], $account['property'], $account['profile']);

                    if ((int) $data['code'] === 200) {
                        $profile = [
                            'id'                => $data['data']['id'],
                            'account_id'        => $data['data']['accountId'],
                            'property_id'       => $data['data']['webPropertyId'],
                            'property_id_int'   => $data['data']['internalWebPropertyId'],
                            'name'              => $data['data']['name'],
                            'url'               => $data['data']['websiteUrl'],
                            'timezone'          => $data['data']['timezone'],
                            'editedon'          => date('Y-m-d H:i:s', strtotime($data['data']['updated'])),
                            'created'           => date('Y-m-d H:i:s', strtotime($data['data']['created']))
                        ];

                        $this->modx->getCacheManager()->set('googleanalytics/' . $account['profile'], $profile, (int) $this->getOption('cache_lifetime'));

                        return $profile;
                    }
                }
            }
        }

        return false;
    }
}
