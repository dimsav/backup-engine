<?php namespace Dimsav\PhpMysqlBackup;

class ProjectManager {

    private $projects = array();

    public function getProjects()
    {
        if ($this->projects) return $this->projects;

        $projectNames = array_keys(Config::get('projects.projects'));

        foreach ($projectNames as $projectName)
        {
            $this->projects[] = new Project($projectName);
        }

        return $this->projects;
    }
}