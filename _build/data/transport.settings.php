<?php
/**
 * @package analytics
 * @subpackage build
*/
     
     
$settings['analytics_accountId']= $modx->newObject('modSystemSetting');
$settings['analytics_accountId']->fromArray(array(
    'key' => 'analytics_accountId',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'analytics',
    'area' => '',
),'',true,true);

$settings['analytics_profileId']= $modx->newObject('modSystemSetting');
$settings['analytics_profileId']->fromArray(array(
    'key' => 'analytics_profileId',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'analytics',
    'area' => '',
),'',true,true);

$settings['analytics_sessionToken']= $modx->newObject('modSystemSetting');
$settings['analytics_sessionToken']->fromArray(array(
    'key' => 'analytics_sessionToken',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'analytics',
    'area' => '',
),'',true,true);

$settings['analytics_webPropertyId']= $modx->newObject('modSystemSetting');
$settings['analytics_webPropertyId']->fromArray(array(
    'key' => 'analytics_webPropertyId',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'analytics',
    'area' => '',
),'',true,true);

$settings['analytics_days']= $modx->newObject('modSystemSetting');
$settings['analytics_days']->fromArray(array(
    'key' => 'analytics_days',
    'value' => '7',
    'xtype' => 'textfield',
    'namespace' => 'analytics',
    'area' => '',
),'',true,true);

$settings['analytics_cachingtime']= $modx->newObject('modSystemSetting');
$settings['analytics_cachingtime']->fromArray(array(
    'key' => 'analytics_cachingtime',
    'value' => '3600',
    'xtype' => 'textfield',
    'namespace' => 'analytics',
    'area' => '',
),'',true,true);

$settings['analytics_sitename']= $modx->newObject('modSystemSetting');
$settings['analytics_sitename']->fromArray(array(
    'key' => 'analytics_sitename',
    'value' => '',
    'xtype' => 'textfield',
    'namespace' => 'analytics',
    'area' => '',
),'',true,true);

$settings['analytics_activetabs']= $modx->newObject('modSystemSetting');
$settings['analytics_activetabs']->fromArray(array(
    'key' => 'analytics_activetabs',
    'value' => '{"visitors":true,"traffic-sources":true,"top-content":true,"goals":true,"keywords":true,"sitesearch":true}',
    'xtype' => 'textfield',
    'namespace' => 'analytics',
    'area' => '',
),'',true,true);

return $settings;