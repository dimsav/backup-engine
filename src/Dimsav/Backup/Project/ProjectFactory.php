<?php namespace Dimsav\Backup\Project;

class ProjectFactory
{
    /**
     * @var Project
     */
    private $project;

    public function make($config)
    {
        $this->initializeProject($config);
        $this->parsePassword($config);

        return $this->project;
    }

    /**
     * @param $config
     */
    private function parsePassword($config)
    {
        if (isset($config['password'])) {
            $this->project->setPassword($config['password']);
        }
    }

    /**
     * @param $config
     * @return mixed
     * @throws \InvalidArgumentException
     */
    private function initializeProject($config)
    {
        if (!isset($config['name'])) {
            throw new \InvalidArgumentException;
        }
        $this->project = new Project($config['name']);
    }
}
