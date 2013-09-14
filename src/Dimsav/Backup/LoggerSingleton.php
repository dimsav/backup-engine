<?php namespace Dimsav\Backup;

use Monolog\Logger;

class LoggerSingleton {

    /** @var LoggerSingleton */
    private static $instance;

    /**
     * @return Logger
     */
    public static function getInstance()
    {
        if ( ! self::$instance)
        {
            self::$instance = new Logger('backups');
        }
        return self::$instance;
    }
}