<?php namespace Dimsav\Backup;

use Illuminate\Config\FileLoader;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Config\Repository;


class Config {

    private static $instance;

    public static function get($key, $default = null)
    {
        if ( ! self::$instance )
        {
            self::validate();
            $fileLoader = new FileLoader(new Filesystem, __DIR__.'/../../../config');
            self::$instance = new Repository($fileLoader, 'production');
        }
        return self::$instance->get($key, $default);
    }

    private static function validate()
    {
        $configDir = __DIR__.'/../../../config';
        if ( ! is_dir($configDir))
        {
            throw new \Exception("The configuration directory is missing. \n$configDir");
        }
    }
}

