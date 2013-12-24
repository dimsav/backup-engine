<?php namespace Dimsav\Backup;

class ProjectRepository {

    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return Project[]
     */
    public function all()
    {
        $projects = array();
        $projectNames = array_keys($this->config->get('projects.projects'));

        foreach ($projectNames as $projectName)
        {
            $projects[] = new Project($this->config, $projectName);
        }

        return $projects;
    }

    /**
     * @return Project[]
     */
    public function withPaths()
    {
        $projects = array();

        foreach ($this->all() as $project)
        {
            if ($project->getPaths())
                $projects[] = $project;
        }

        return $projects;
    }

}