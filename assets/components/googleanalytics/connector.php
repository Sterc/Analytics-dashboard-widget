<?php

/**
 * Google Analytics
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

use Sterc\GoogleAnalytics\GoogleAnalytics;

require_once dirname(dirname(dirname(__DIR__))) . '/config.core.php';

require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$googleanalytics = new GoogleAnalytics($modx);

if ($googleanalytics instanceOf GoogleAnalytics) {
    $modx->request->handleRequest([
        'processors_path'   => $googleanalytics->config['processors_path'],
        'location'          => ''
    ]);
}
