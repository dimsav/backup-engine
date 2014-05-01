<?php namespace Dimsav\Backup;

use Dimsav\Backup\Element\ElementFactory;
use Dimsav\Backup\Project\Project;
use Dimsav\Backup\Project\ProjectFactory;
use Dimsav\Backup\Storage\StorageFactory;
use Dimsav\Backup\Utilities\DefaultsParser;

class Application
{

    private $projectFactory;

    public function __construct($config)
    {
        $configParser = new DefaultsParser($config);
        $config = $configParser->parse();

        $this->projectFactory = new ProjectFactory(
            $config,
            new ElementFactory($config['projects']),
            new StorageFactory($config));
    }

    public function backup($tempDir)
    {
        $projects = $this->projectFactory->makeAll();

        foreach ($projects as $project)
        {
            $project->backup($tempDir);
        }
    }



}