<?php namespace Dimsav\Backup;

use Monolog\Logger;

class LoggerSingleton extends Logger {

    /** @var LoggerSingleton */
    private static $instance;

    /**
     * @return LoggerSingleton
     */
    public static function getInstance()
    {
        if ( ! self::$instance)
        {
            self::$instance = new static('backups');
        }
        return self::$instance;
    }
}