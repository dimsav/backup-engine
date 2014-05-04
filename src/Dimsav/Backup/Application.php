<?php namespace Dimsav\Backup;

use Dimsav\Backup\Element\ElementFactory;
use Dimsav\Backup\Project\Project;
use Dimsav\Backup\Project\ProjectFactory;
use Dimsav\Backup\Storage\StorageFactory;
use Dimsav\Backup\Utilities\DefaultsParser;

class Application
{

    private $projectFactory;
    private $config;
    private $tempDir;

    public function __construct($config)
    {
        $this->config = $config;
        $this->loadConfig();

        $configParser = new DefaultsParser($config);
        $this->config = $configParser->parse();

        $this->projectFactory = new ProjectFactory(
            $this->config,
            new ElementFactory($this->config['projects']),
            new StorageFactory($this->config));
    }

    public function backup()
    {
        $projects = $this->projectFactory->makeAll();

        foreach ($projects as $project)
        {
            $this->resetTempDir();
            $project->backup($this->tempDir);
        }
    }

    private function loadConfig()
    {
        date_default_timezone_set($this->config['app']['timezone']);
        set_time_limit($this->config['app']['time_limit']);

        $this->tempDir = $this->config['app']['temp_dir'];
        register_shutdown_function(array($this, 'shutdown'));
    }

    private function resetTempDir()
    {
        $this->deleteTempDir();
        mkdir($this->tempDir, 0777, true);
    }

    private function deleteTempDir()
    {
        if (is_dir($this->tempDir)) exec('rm -rf ' . realpath($this->tempDir));
    }

    public function shutdown()
    {
        $this->deleteTempDir();
    }

}