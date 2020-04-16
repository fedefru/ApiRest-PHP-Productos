<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit08f551eb83dfb64c396c5b9fa0948c6e
{
    public static $prefixesPsr0 = array (
        'S' => 
        array (
            'Slim' => 
            array (
                0 => __DIR__ . '/..' . '/slim/slim',
            ),
        ),
    );

    public static $classMap = array (
        'PiramideUploader' => __DIR__ . '/../..' . '/piramide-uploader/PiramideUploader.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit08f551eb83dfb64c396c5b9fa0948c6e::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit08f551eb83dfb64c396c5b9fa0948c6e::$classMap;

        }, null, ClassLoader::class);
    }
}