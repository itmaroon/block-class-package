<?php

namespace Itmar\Autoloader;

class Loader
{
    protected static $prefixes = [];

    public static function register()
    {
        if (!self::$prefixes) {
            self::$prefixes = require __DIR__ . '/../../vendor/composer/autoload_psr4.php';
        }

        spl_autoload_register([__CLASS__, 'autoload']);
    }

    public static function autoload($class)
    {
        foreach (self::$prefixes as $prefix => $dirs) {
            if (strpos($class, $prefix) === 0) {
                foreach ($dirs as $dir) {
                    $relative_class = substr($class, strlen($prefix));
                    $file = rtrim($dir, '/\\') . '/' . str_replace('\\', '/', $relative_class) . '.php';
                    if (file_exists($file)) {
                        require $file;
                        return;
                    }
                }
            }
        }
    }
}
