<?php namespace Dimsav\Backup;

class Application {

    /** @var  Logger */
    private $log;


    public function __construct()
    {
        $this->config = new Config;
        $this->log = new Logger($this->config);
    }

    public function run()
    {
        $this->log->info('Initiating backup.');
    }
}