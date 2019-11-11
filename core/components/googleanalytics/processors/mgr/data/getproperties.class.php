<?php

/**
 * Google Analytics
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class GoogleAnalyticsGetPropertiesProcessor extends modObjectProcessor
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
     * @access public.
     * @return Mixed.
     */
    public function initialize()
    {
        $this->modx->getService('googleanalytics', 'GoogleAnalytics', $this->modx->getOption('googleanalytics.core_path', null, $this->modx->getOption('core_path') . 'components/googleanalytics/') . 'model/googleanalytics/');

        return parent::initialize();
    }

    /**
     * @access public.
     * @return Mixed.
     */
    public function process()
    {
        $data = $this->modx->googleanalytics->getRequest()->getAccountProperties($this->getProperty('account'));

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

return 'GoogleAnalyticsGetPropertiesProcessor';
