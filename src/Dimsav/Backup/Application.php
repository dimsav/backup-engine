<?php namespace Dimsav\Backup;

class Application {

    /** @var  Logger */
    private $log;


    public function __construct(Config $config = null)
    {
        $this->config = $config ?: new Config;
        $this->log = new Logger($this->config);
    }

    public function run()
    {
        $this->log->info('Initiating backup.');

        $this->log->info('End of backup script.');
    }
}