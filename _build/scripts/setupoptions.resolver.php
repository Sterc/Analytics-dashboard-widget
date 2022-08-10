<?php
use xPDO\Transport\xPDOTransport;
use MODX\Revolution\modSystemSetting;

$package  = 'GoogleAnalytics';
$settings = ['user_name', 'user_email'];
$success  = false;

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
    case xPDOTransport::ACTION_UPGRADE:
        foreach ($settings as $key) {
            if (isset($options[$key])) {
                $setting = $transport->xpdo->getObject(modSystemSetting::class, ['key' => strtolower($package) . '.' . $key]);
                if ($setting) {
                    $setting->set('value', $options[$key]);
                    $setting->save();
                }
            }
        }

        $success = true;

        break;
    case xPDOTransport::ACTION_UNINSTALL:
        $success = true;

        break;
}

return $success;
