<?php namespace Dimsav\PhpMysqlBackup;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Application {

    /** @var  Logger */
    private $log;

    public function run()
    {
        $this->setup();
        if ($this->isLoggingEnabled()) $this->setupLog();

        $this->log->addInfo('Initiating backup.');

    }

    private function setup()
    {
        if (Config::get('app.debug', true))
        {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        }

        date_default_timezone_set(Config::get('app.timezone', 'Europe/Berlin'));

        set_time_limit (Config::get('app.time_limit', 0));

        $this->log = new Logger('backups');
    }

    private function isLoggingEnabled()
    {
        return (Config::get('app.log') || Config::get('app.error_log'));
    }

    private function setupLog()
    {
        if (Config::get('app.log'))
        {
            if ( ! is_dir(dirname(Config::get('app.log'))))
            {
                mkdir(dirname(Config::get('app.log')), 0777, true);
            }
            $streamHandler = new StreamHandler(Config::get('app.log'));
            $streamHandler->setFormatter(new LineFormatter("[%datetime%] %level_name%: %message%\n"));
            $this->log->pushHandler($streamHandler);
        }

        if (Config::get('app.error_log'))
        {
            if ( ! is_dir(dirname(Config::get('app.error_log'))))
            {
                mkdir(dirname(Config::get('app.error_log')), 0777, true);
            }
            $streamHandler = new StreamHandler(Config::get('app.error_log'), Logger::NOTICE);
            $streamHandler->setFormatter(new LineFormatter("[%datetime%] %level_name%: %message%\n"));
            $this->log->pushHandler($streamHandler);
        }
    }
}