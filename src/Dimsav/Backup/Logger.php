<?php namespace Dimsav\Backup;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as Log;

class Logger {

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->log = new Log('backups');

        $this->setup();
    }

    public function info($message, $context = array())
    {
        $this->log->addInfo($message, $context);
    }

    public function error($message, $context = array())
    {
        $this->log->addError($message, $context);
    }

    private function setup()
    {
        if ($this->config->get('app.log'))
        {
            $this->mkdirIfNotDir($this->config->get('app.log'));
            $streamHandler = new StreamHandler($this->config->get('app.log'));
            $streamHandler->setFormatter(new LineFormatter("[%datetime%] %level_name%: %message%\n"));
            $this->log->pushHandler($streamHandler);
        }

        if ($this->config->get('app.error_log'))
        {
            $this->mkdirIfNotDir($this->config->get('app.error_log'));
            $streamHandler = new StreamHandler($this->config->get('app.error_log'), Log::NOTICE);
            $streamHandler->setFormatter(new LineFormatter("[%datetime%] %level_name%: %message%\n"));
            $this->log->pushHandler($streamHandler);
        }

        if ($this->config->get('app.debug', true))
        {
            $streamHandler = new StreamHandler('php://stderr', Log::DEBUG);
            $streamHandler->setFormatter(new LineFormatter("[%datetime%] %level_name%: %message%\n"));
            $this->log->pushHandler($streamHandler);
        }
    }

    private function mkdirIfNotDir($dir)
    {
        if ( ! is_dir(dirname($dir)))
            mkdir(dirname($dir), 0777, true);
    }
}