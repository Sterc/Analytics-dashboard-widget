<?php
/**
 * analytics
 *
 * Copyright 2011 by Wieger Sloot, Sterc Internet & Marketing <modx@sterc.nl>
 *
 * analytics is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * analytics is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * analytics; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package analytics
 */
/**
 * analytics build script
 *
 * @package analytics
 * @subpackage build
 */
$mtime = microtime();
$mtime = explode(" ", $mtime);
$mtime = $mtime[1] + $mtime[0];
$tstart = $mtime;
set_time_limit(0);

/* define package */
define('PKG_NAME','Analyticsdashboard');
define('PKG_NAME_LOWER',strtolower(PKG_NAME));
define('PKG_VERSION','1.0.1');
define('PKG_RELEASE','pl');



/* define sources */
$root = dirname(dirname(__FILE__)).'/';
//print($root);exit;
$sources= array (
    'root' => $root.'analytics/',
    'build' => $root .'_build/',
    'data' => $root . '_build/data/',
    'source_core' => $root.'core/components/analytics',
    'source_assets' => $root.'assets/components/analytics',
    'docs' => $root.'core/components/analytics'.'/docs/',

);

//print_r($sources);exit;
unset($root);

/* override with your own defines here (see build.config.php) */
require_once dirname(__FILE__) . '/build.config.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
require_once $sources['build'] . 'includes/functions.php';

$modx= new modX();

$modx->initialize('mgr');
$modx->setLogLevel(xPDO::LOG_LEVEL_INFO);
if (!XPDO_CLI_MODE) { echo '<pre>'; }
$modx->setLogTarget('ECHO');

$modx->loadClass('transport.modPackageBuilder','',false, true);
$builder = new modPackageBuilder($modx);
$builder->createPackage(PKG_NAME_LOWER,PKG_VERSION,PKG_RELEASE);
$builder->registerNamespace('analytics',false,true,'{core_path}components/analytics'.'/');

/* create category */
$category= $modx->newObject('modCategory');
$category->set('id',1);
$category->set('category',PKG_NAME);


/* load system settings */
$settings = include $sources['data'].'transport.settings.php';
if (is_array($settings) && !empty($settings)) {
    $attributes= array(
        xPDOTransport::UNIQUE_KEY => 'key',
        xPDOTransport::PRESERVE_KEYS => true,
        xPDOTransport::UPDATE_OBJECT => true,
    );
    foreach ($settings as $setting) {
        $vehicle = $builder->createVehicle($setting,$attributes);
        $builder->putVehicle($vehicle);
    }
    $modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($settings).' System Settings.'); flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not package System Settings.');
}
unset($settings,$setting);

/**
 *  Dashboard Widgets
 */
$widgets = array();
$widgets[1]= $modx->newObject('modDashboardWidget');
$widgets[1]->fromArray(array (
  'name' => 'analytics',
  'description' => 'Google Analytics dashboard widget',
  'type' => 'file',
  'size' => 'double',
  'content' => '[[++core_path]]components/analytics/elements/widget/widget.analytics.php',
  'namespace' => 'analytics',
  'lexicon' => 'analytics:default',
), '', true, true);

/* modDashboardWidget */
if (is_array($widgets)) {
    $attributes = array (
        xPDOTransport::PRESERVE_KEYS => false,
        xPDOTransport::UPDATE_OBJECT => true,
        xPDOTransport::UNIQUE_KEY => array ('name'),
    );
    $ct = count($widgets);
    $idx = 0;
    foreach ($widgets as $widget) {
        $idx++;
		$vehicle = $builder->createVehicle($widget,$attributes);
		$builder->putVehicle($vehicle);
    }
    $modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($widgets).' default dashboard widgets.'); flush();
} else {
    $xpdo->log(xPDO::LOG_LEVEL_ERROR,'Could not load dashboard widgets!'); flush();
}
unset ($widgets,$widget,$attributes,$ct,$idx);

/* create category vehicle */
$attr = array(
    xPDOTransport::UNIQUE_KEY => 'category',
    xPDOTransport::PRESERVE_KEYS => false,
    xPDOTransport::UPDATE_OBJECT => true,
    xPDOTransport::RELATED_OBJECTS => true,
);
$vehicle = $builder->createVehicle($category,$attr);
$vehicle->resolve('file',array(
    'source' => $sources['source_core'],
    'target' => "return MODX_CORE_PATH . 'components/';",
));
$vehicle->resolve('file',array(
    'source' => $sources['source_assets'],
    'target' => "return MODX_ASSETS_PATH . 'components/';",
));
$builder->putVehicle($vehicle);





$builder->setPackageAttributes(array(
    'license' => file_get_contents($sources['docs'] . 'license.txt'),
    'readme' => file_get_contents($sources['docs'] . 'readme.txt'),
    'changelog' => file_get_contents($sources['docs'] . 'changelog.txt')
));


$modx->log(modX::LOG_LEVEL_INFO,'Packaged in package attributes.'); flush();

$builder->pack();

$mtime= microtime();
$mtime= explode(" ", $mtime);
$mtime= $mtime[1] + $mtime[0];
$tend= $mtime;
$totalTime= ($tend - $tstart);
$totalTime= sprintf("%2.4f s", $totalTime);

$modx->log(xPDO::LOG_LEVEL_INFO,"\n<br />Package Built.<br />\nExecution time: {$totalTime}\n");

exit ();