<?php namespace Dimsav\Backup\Project;

use Dimsav\Backup\Element\ElementFactory;
use Dimsav\Backup\Storage\StorageFactory;

class ProjectFactory
{
    private $config;
    /**
     * @var \Dimsav\Backup\Element\ElementFactory
     */
    private $elementFactory;

    /**
     * Project being filled by make()
     * @var \Dimsav\Backup\Project\Project
     */
    private $project;

    /**
     * @var ProjectFactory
     */
    private $storageFactory;

    public function __construct($fullConfig, ElementFactory $elementFactory, StorageFactory $storageFactory)
    {
        $this->config = $fullConfig;
        $this->elementFactory = $elementFactory;
        $this->storageFactory = $storageFactory;
    }

    public function make($projectName)
    {
        $this->project = new Project();
        $this->project->setName($projectName);
        $this->assignElementsTo($projectName);
        $this->assignProjectsTo($projectName);
        return $this->project;
    }

    private function assignElementsTo($projectName)
    {
        $elements = $this->elementFactory->makeAll($projectName);
        $this->project->setElements($elements);
    }

    private function assignProjectsTo($projectName)
    {
        $projects = $this->storageFactory->makeAll($projectName);
        $this->project->setStorages($projects);
    }

}
