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

        $this->setup();
        $this->setupLog();
        $this->validate();
    }

    public function run()
    {
        $this->log->addInfo('Initiating backup.');

        $this->projectManager->compressProjectsFiles();

    }

    private function setup()
    {
        date_default_timezone_set(Config::get('app.timezone', 'Europe/Berlin'));

        set_time_limit (Config::get('app.time_limit', 0));
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

    private function validate()
    {
        if ( ! count(Config::get('app.projects')))
        {
            $this->log->addAlert("You don't have any projects defined. Terminating.");
            die;
        }
    }
}