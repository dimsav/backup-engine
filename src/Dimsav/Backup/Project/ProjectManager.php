<?php namespace Dimsav\Backup\Project;

use Dimsav\Backup\Storage\StorageManager;

class ProjectManager
{

    private $globalConfig;
    /**
     * @var ProjectFactory
     */
    private $projectFactory;

    /**
     * @var StorageManager
     */
    private $storageManager;

    public function __construct(
        ProjectFactory $projectFactory, StorageManager $storageManager
    )
    {
        $this->projectFactory = $projectFactory;
        $this->storageManager = $storageManager;
    }

//    private function make($projectName)
//    {
//        $projectConfig = $this->getProjectConfig($projectName);
//        $project = $this->projectFactory->make($projectConfig);
//
//        $storages = $this->getProjectStorages();
//        foreach ($storages as $storage)
//        {
//            $project->addStorage($storage);
//        }
//
//        $databases = $this->getProjectDatabases();
//        foreach ($databases as $database)
//        {
//            $project->addDatabase($database);
//        }
//
//        $paths = $this->getProjectPaths();
//        foreach ($paths as $path)
//        {
//            $project->addPath($path);
//        }
//
//        $excludes = $this->getProjectExcludes();
//        foreach ($excludes as $exclude)
//        {
//            $project->addExclude($exclude);
//        }
//
//        return $project;
//    }

    public function getAll()
    {
        $projects = array();
        foreach($this->getProjectNames() as $projectName)
        {
            $projects[] = $this->make($projectName, $projects);
        }
        return $projects;
    }

    /**
     * @param $projectName
     * @return array
     */
    private function make($projectName)
    {
        $projectConfig = $this->getProjectConfig($projectName);
        $project = $this->projectFactory->make($projectConfig);
        $this->addProjectStorages($project, $projectConfig);

        return $project;
    }

    private function getProjectConfig($projectName)
    {
        $config = $this->globalConfig['projects'][$projectName];
        $config['name'] = $projectName;
        return $config;
    }

    public function setConfig($config)
    {
        $this->validateGlobalConfig($config);
        $this->validateProjectsConfig($config);

        $this->globalConfig = $config;
    }

    public function getProjectNames()
    {
        return array_keys($this->globalConfig['projects']);
    }

    private function validateGlobalConfig($config)
    {
        $exception = new \InvalidArgumentException('At least one project must be defined. Check your configuration.');
        if ( ! isset($config['projects']))
        {
            throw $exception;
        }

        if ( ! is_array($config['projects']))
        {
            throw new \InvalidArgumentException('The project\'s configuration must be an array.');
        }

        if (count($config['projects']) == 0)
        {
            throw $exception;
        }
    }

    private function validateProjectsConfig($config)
    {
        foreach ($config['projects'] as $projectName => $projectConfig)
        {
            $this->validateProjectConfig($projectName, $projectConfig);
        }
    }

    private function validateProjectConfig($name, $config)
    {
        if ( ! isset($config['storages']))
        {
            throw new \InvalidArgumentException("The project '$name' has no storages assigned. Check your configuration'");
        }
    }

    private function addProjectStorages(Project $project, $projectConfig)
    {
        $storages = (array) $projectConfig['storages'];
        foreach ($storages as $storageName)
        {
            $storage = $this->storageManager->make($storageName);
            $project->addStorage($storage);
        }
    }
}
