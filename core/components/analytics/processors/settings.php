<?php
/**
 * @var modX $modx
 * @var array $scriptProperties
 */
$setting = trim($_POST['setting']);
$value = trim($_POST['value']);
unset($_POST['setting']);
unset($_POST['value']);
$days = $modx->getOption('analytics_days');

//$modx->setOption($setting, $value);


/** @var modSystemSetting $analyticsSetting */
$analyticsSetting = $modx->getObject('modSystemSetting',$setting);

$analyticsSetting->set('value', $value);
$analyticsSetting->save();
$modx->getCacheManager();
$modx->cacheManager->delete($days.'-analytics');

$modx->cacheManager->deleteTree($modx->getOption('core_path',null,MODX_CORE_PATH).'cache/mgr/smarty/',array(
   'deleteTop' => false,
    'skipDirs' => false,
    'extensions' => array('.cache.php','.php'),
));
$modx->reloadConfig();
return $modx->error->success();