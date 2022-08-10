<?php
use xPDO\Transport\xPDOTransport;
use MODX\Revolution\modDashboard;
use MODX\Revolution\modDashboardWidget;
use MODX\Revolution\modDashboardWidgetPlacement;

$package = 'Google Analytics';

$widgets = [[
    'name' => 'googleanalytics.widget_visitors'
], [
    'name' => 'googleanalytics.widget_realtime'
]];

$success = false;

if ($transport->xpdo) {
    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
        case xPDOTransport::ACTION_UPGRADE:
            $modx =& $transport->xpdo;

            foreach ($modx->getCollection(modDashboard::class) as $dashboard) {
                foreach ($widgets as $key => $value) {
                    $widget = $modx->getObject(modDashboardWidget::class, ['name' => $value['name']]);
                    if ($widget) {
                        $placement = $modx->getObject(modDashboardWidgetPlacement::class, [
                            'dashboard' => $dashboard->get('id'),
                            'widget'    => $widget->get('id')
                        ]);

                        if (!$placement) {
                            $placement = $modx->newObject(modDashboardWidgetPlacement::class);

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
