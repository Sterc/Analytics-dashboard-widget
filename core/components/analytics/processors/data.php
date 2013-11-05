<?php
/**
 * @var modX $modx
 * @var array $scriptProperties
 */
$setting = $modx->getObject('modSystemSetting', 'analytics_days');
$days = $setting->get('value');
$modx->getCacheManager();

$analytics = $modx->cacheManager->get($days.'-analytics');

$data = trim($_GET['data']);
$format = trim($_GET['format']);
if(isset($analytics)){

	if($format == 'json'){
		print(json_encode($analytics[$data]));
	}else{
		print_r($analytics[$data]);

	}
}
die();