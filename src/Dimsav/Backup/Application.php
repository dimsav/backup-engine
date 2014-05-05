<?php namespace Dimsav\Backup;

use Dimsav\Backup\Element\ElementFactory;
use Dimsav\Backup\Project\ProjectFactory;
use Dimsav\Backup\Storage\StorageFactory;
use Dimsav\Backup\Utilities\DefaultsParser;
use Dimsav\Backup\Validator\StorageValidator;

class Application
{

    /**
     * @var ProjectFactory
     */
    private $projectFactory;

    /**
     * @var StorageFactory
     */
    private $storageFactory;
    /**
     * @var StorageValidator
     */
    private $storageValidator;

    private $config;
    private $tempDir;
    private $timezone;
    private $timeLimit;
    private $errors;


    public function __construct($config)
    {
        $this->setProperties($config);
        $this->setup();
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

    private function setProperties($config)
    {
        $this->tempDir = isset($config['app']['temp_dir']) ? $config['app']['temp_dir'] : null;
        $this->timezone = isset($config['app']['timezone']) ? $config['app']['timezone'] : null;
        $this->timeLimit = isset($config['app']['time_limit']) ? $config['app']['time_limit'] : null;

        $configParser = new DefaultsParser($config);
        $this->config = $configParser->parse();

        $this->storageFactory = new StorageFactory($this->config);
        $this->projectFactory = new ProjectFactory(
            $this->config,
            new ElementFactory($this->config['projects']),
            $this->storageFactory
        );
        $this->storageValidator = new StorageValidator($this->storageFactory);
    }

    private function setup()
    {
        date_default_timezone_set($this->timezone);
        set_time_limit($this->timeLimit);
        register_shutdown_function(array($this, 'shutdown'));
    }

    public function hasErrors()
    {
        $this->storageValidator->validate();
        $this->errors = $this->storageValidator->getValidationErrorsString();
        return $this->errors ? true : false;
    }

    public function getErrors()
    {
        return $this->errors;
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