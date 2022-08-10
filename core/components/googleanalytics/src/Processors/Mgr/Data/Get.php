<?php
namespace Sterc\GoogleAnalytics\Processors\Mgr\Data;

use MODX\Revolution\Processors\Processor;
use Sterc\GoogleAnalytics\GoogleAnalytics;

class Get extends Processor
{
    /**
     * @access public.
     * @var Array.
     */
    public $languageTopics = ['googleanalytics:default'];

    /**
     * @access public.
     * @var String.
     */
    public $objectType = 'googleanalytics.accounts';

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

        $this->setDefaultProperties([
            'history'   => $this->googleanalytics->getOption('history'),
            'limit'     => 0
        ]);

        return parent::initialize();
    }

    /**
     * @access public.
     * @return String.
     */
    public function process()
    {
        $output     = [];
        $profile    = $this->googleanalytics->getAuthorizedProfile();

        if ($profile) {
            $type   = $this->getProperty('data');

            $this->googleanalytics->getRequest()->setHistory($this->getProperty('history'));

            if (!empty($type)) {
                $data = $this->googleanalytics->getRequest()->getData($type);

                if ((int) $data['code'] === 200) {
                    $output = $data['data'];
                }
            }
        }

        $limit = (int) $this->getProperty('limit');
        if ($limit >= 1) {
            return $this->outputArray(array_slice($output, $this->getProperty('start'), $limit), count($output));
        }

        return $this->outputArray($output, count($output));
    }
}
