<?php namespace Dimsav\Backup\Project;

use Dimsav\Backup\Element\ElementFactory;

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

    public function __construct($fullConfig, ElementFactory $elementFactory)
    {
        $this->config = $fullConfig;
        $this->elementFactory = $elementFactory;
    }

    public function make($projectName)
    {
        $this->project = new Project();
        $this->project->setName($projectName);
        $this->assignElementsTo($projectName);
        return $this->project;
    }

    private function assignElementsTo($projectName)
    {
        $elements = $this->elementFactory->makeAll($projectName);
        $this->project->setElements($elements);
    }

}
