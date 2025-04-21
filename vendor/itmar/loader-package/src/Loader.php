<?php

namespace Itmar\Autoloader;

class Loader
{
    protected static $prefixes = [];

    public static function register($psr4_path = null)
    {
        if (!self::$prefixes) {
            if (!$psr4_path) {
                throw new \InvalidArgumentException('Path to autoload_psr4.php is required.');
            }
            self::$prefixes = require $psr4_path;
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
