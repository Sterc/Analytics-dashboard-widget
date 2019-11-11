<?php

/**
 * Google Analytics
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

$package = 'Google Analytics';

$widgets = [[
    'name' => 'googleanalytics.widget_visitors'
], [
    'name' => 'googleanalytics.widget_realtime'
]];

$success = false;

if ($object->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $object->xpdo;

            foreach ($modx->getCollection('modDashboard') as $dashboard) {
                foreach ($widgets as $key => $value) {
                    $widget = $modx->getObject('modDashboardWidget', [
                        'name' => $value['name']
                    ]);

                    if ($widget) {
                        $placement = $modx->getObject('modDashboardWidgetPlacement', [
                            'dashboard' => $dashboard->get('id'),
                            'widget'    => $widget->get('id')
                        ]);

                        if (!$placement) {
                            $placement = $modx->newObject('modDashboardWidgetPlacement');

                            $placement->set('dashboard', $dashboard->get('id'));
                            $placement->set('widget', $widget->get('id'));
                            $placement->set('rank', $key);

                            $placement->save();
                        }
                    }
                }
            }

            $success = true;

            break;
        case xPDOTransport::ACTION_UNINSTALL:
            $success = true;

            break;
    }
}

return $success;
