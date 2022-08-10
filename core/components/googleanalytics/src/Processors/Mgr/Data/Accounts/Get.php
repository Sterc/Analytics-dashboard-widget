<?php
namespace Sterc\GoogleAnalytics\Processors\Mgr\Data\Accounts;

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
     * @return Mixed.
     */
    public function process()
    {
        $data = $this->googleanalytics->getRequest()->getAccounts();

        if ((int) $data['code'] === 200) {
            $output = [];

            if (isset($data['data']['items'])) {
                $query = $this->getProperty('query');

                foreach ((array) $data['data']['items'] as $value) {
                    $value = [
                        'id'    => $value['id'],
                        'name'  => $value['name']
                    ];

                    if (!empty($query)) {
                        if (stripos($value['name'], $query) !== false) {
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
