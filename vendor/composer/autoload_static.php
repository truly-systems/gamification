<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit593eb004a9b6440b88f179c689dc45e0
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SlevomatCodingStandard\\' => 23,
        ),
        'P' => 
        array (
            'PHPStan\\PhpDocParser\\' => 21,
        ),
        'D' => 
        array (
            'Dealerdirect\\Composer\\Plugin\\Installers\\PHPCodeSniffer\\' => 55,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SlevomatCodingStandard\\' => 
        array (
            0 => __DIR__ . '/..' . '/slevomat/coding-standard/SlevomatCodingStandard',
        ),
        'PHPStan\\PhpDocParser\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpstan/phpdoc-parser/src',
        ),
        'Dealerdirect\\Composer\\Plugin\\Installers\\PHPCodeSniffer\\' => 
        array (
            0 => __DIR__ . '/..' . '/dealerdirect/phpcodesniffer-composer-installer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit593eb004a9b6440b88f179c689dc45e0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit593eb004a9b6440b88f179c689dc45e0::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit593eb004a9b6440b88f179c689dc45e0::$classMap;

        }, null, ClassLoader::class);
    }
}