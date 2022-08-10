<?php
namespace Sterc\GoogleAnalytics\Processors\Mgr\Data\Profiles;

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

        return parent::initialize();
    }

    /**
     * @access public.
     * @return String.
     */
    public function process()
    {
        $output = [];

        $data = $this->googleanalytics->getRequest()->getAccountProfiles($this->getProperty('account'), $this->getProperty('property'));

        if ((int) $data['code'] === 200) {
            $output = [];

            if (isset($data['data']['items'])) {
                $query = $this->getProperty('query');

                foreach ((array) $data['data']['items'] as $value) {
                    $value = [
                        'id'    => $value['id'],
                        'name'  => $value['name'],
                        'url'   => $value['websiteUrl']
                    ];

                    if (!empty($query)) {
                        if (stripos($value['id'], $query) !== false) {
                            $output[] = $value;
                        } else if (stripos($value['name'], $query) !== false) {
                            $output[] = $value;
                        } else if (stripos($value['url'], $query) !== false) {
                            $output[] = $value;
                        }
                    } else {
                        $output[] = $value;
                    }
                }
            }

            $sort = [];

            foreach ($output as $key => $value) {
                $sort[$key] = $value['name'];
            }

            array_multisort($sort, SORT_ASC, $output);

            return $this->outputArray($output);
        }

        return $this->failure($data['message']);
    }
}
