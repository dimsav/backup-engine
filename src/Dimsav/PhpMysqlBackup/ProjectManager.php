<?php namespace Dimsav\PhpMysqlBackup;

class ProjectManager {

    private static $projects = array();

    public function getProjects()
    {
        if (self::$projects) return self::$projects;

        $projectNames = array_keys(Config::get('projects.projects'));

        foreach ($projectNames as $projectName)
        {
            self::$projects[] = new Project($projectName);
        }

        return self::$projects;
    }
}