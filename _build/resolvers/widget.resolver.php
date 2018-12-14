<?php
/**
 * Google Analytics setup options resolver
 *
 * @package Google Analytics
 * @subpackage build
 */
$package = str_replace(' ', '', 'Google Analytics');

/** @var $widgets */
$widgets = array(
    array(
        'name'        => 'googleanalytics.widget_visitors',
        'description' => 'googleanalytics.widget_visitors_desc',
        'type'        => 'file',
        'content'     => '[[++core_path]]components/googleanalytics/elements/widgets/visitors.widget.php',
        'namespace'   => 'googleanalytics',
        'lexicon'     => 'googleanalytics:default',
        'size'        => 'half'
    ),
    array(
        'name'        => 'googleanalytics.widget_realtime',
        'description' => 'googleanalytics.widget_realtime_desc',
        'type'        => 'file',
        'content'     => '[[++core_path]]components/googleanalytics/elements/widgets/realtime.widget.php',
        'namespace'   => 'googleanalytics',
        'lexicon'     => 'googleanalytics:default',
        'size'        => 'half'
    )
);


$success = false;
switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        foreach ($widgets as $widget) {
            $widgetObject = $object->xpdo->getObject('modDashboardWidget', array('name' => $widget['name']));
            if (!$widgetObject) {
                $widgetObject = $object->xpdo->newObject('modDashboardWidget');
                $widgetObject->fromArray($widget);
                $widgetObject->save();
                $object->xpdo->log(modX::LOG_LEVEL_INFO, 'Installed widget: ' . $widget['name']);
            }
        }

        $success = true;
        break;
    case xPDOTransport::ACTION_UNINSTALL:

        $success = true;
        break;
}

return $success;
