<?php

/**
 * Google Analytics
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class GoogleAnalyticsGetDataProcessor extends modObjectProcessor
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

        $this->setDefaultProperties([
            'history'   => $this->modx->googleanalytics->getOption('history'),
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
        $profile    = $this->modx->googleanalytics->getAuthorizedProfile();

        if ($profile) {
            $type   = $this->getProperty('data');

            $this->modx->googleanalytics->getRequest()->setHistory($this->getProperty('history'));

            if (!empty($type)) {
                $data = $this->modx->googleanalytics->getRequest()->getData($type);

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

return 'GoogleAnalyticsGetDataProcessor';
