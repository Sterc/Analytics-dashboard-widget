<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/config.core.php';
require_once MODX_CORE_PATH.'config/'.MODX_CONFIG_KEY.'.inc.php';
require_once MODX_CONNECTORS_PATH.'index.php';

$gaCorePath = $modx->getOption('analytics.core_path',null,$modx->getOption('core_path').'components/analytics/');
require_once $gaCorePath.'model/analytics/analytics.class.php';
$modx->analytics = new GoogleAnalytics($modx);

$modx->lexicon->load('analytics:default');

/* handle request */
$path = $modx->getOption('processorsPath',$modx->analytics->config,$gaCorePath.'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));