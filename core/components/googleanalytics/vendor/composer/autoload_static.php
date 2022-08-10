<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfb3a0396bb1ebe2a294342b981f1f6e6
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Sterc\\GoogleAnalytics\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Sterc\\GoogleAnalytics\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitfb3a0396bb1ebe2a294342b981f1f6e6::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfb3a0396bb1ebe2a294342b981f1f6e6::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitfb3a0396bb1ebe2a294342b981f1f6e6::$classMap;

        }, null, ClassLoader::class);
    }
}