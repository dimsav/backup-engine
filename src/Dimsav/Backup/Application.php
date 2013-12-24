<?php namespace Dimsav\Backup;

class Application {

    /** @var  Logger */
    private $log;


    public function __construct(Config $config = null)
    {
        $this->config = $config ?: new Config;
        $this->log = new Logger($this->config);
        $this->manager = new ProjectManager($this->config, $this->log, new ProjectRepository($this->config));
    }

    public function run()
    {
        $this->log->info('Initiating backup.');

        $this->manager->backup();

        $this->log->info('End of backup script.');
    }
}