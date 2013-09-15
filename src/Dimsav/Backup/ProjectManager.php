<?php namespace Dimsav\Backup;

class ProjectManager {

    /** @var LoggerSingleton  */
    private $log;

    private static $projects = array();

    public function __construct()
    {
        $this->log = LoggerSingleton::getInstance();
    }

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