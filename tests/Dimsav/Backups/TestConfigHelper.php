<?php namespace Dimsav\Backups;

use Dimsav\Backup\Config;

class TestConfigHelper {

    public function __construct()
    {
        $this->config = new Config('testing');
    }

    public function excludeProjectsNotMatching($projectName)
    {
        $project = $this->config->get("projects.projects.$projectName");

        if ($project)
        {
            $this->config->set("projects.projects", null);
            $this->config->set("projects.projects.$projectName", $project);
        }
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function getBackupDir($projectName = null)
    {
        return $this->config->get('app.backups_dir')."/{$projectName}";
    }
} 