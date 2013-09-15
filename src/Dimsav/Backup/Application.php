<?php namespace Dimsav\Backup;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Application {

    /** @var  Logger */
    private $log;
    /** @var  ProjectManager */
    private $projectManager;


    public function __construct()
    {
        $this->log = LoggerSingleton::getInstance();
        $this->projectManager = new ProjectManager();
    }

    public function run()
    {
        $this->setup();
        if ($this->isLoggingEnabled()) $this->setupLog();

        $this->log->addInfo('Initiating backup.');

        $this->projectManager->compressProjectsFiles();

    }

    private function setup()
    {
        date_default_timezone_set(Config::get('app.timezone', 'Europe/Berlin'));

        set_time_limit (Config::get('app.time_limit', 0));
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

        if (Config::get('app.debug', true))
        {
            $streamHandler = new StreamHandler('php://stderr', Logger::DEBUG);
            $streamHandler->setFormatter(new LineFormatter("[%datetime%] %level_name%: %message%\n"));
            $this->log->pushHandler($streamHandler);
        }
    }
}