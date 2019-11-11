<?php

/**
 * Google Analytics
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

require_once dirname(dirname(dirname(__DIR__))) . '/config.core.php';

require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$modx->getService('googleanalytics', 'GoogleAnalytics', $modx->getOption('googleanalytics.core_path', null, $modx->getOption('core_path') . 'components/googleanalytics/') . 'model/googleanalytics/');

if ($modx->googleanalytics instanceOf GoogleAnalytics) {
    $modx->request->handleRequest([
        'processors_path'   => $modx->googleanalytics->config['processors_path'],
        'location'          => ''
    ]);
}
