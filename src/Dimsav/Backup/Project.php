<?php namespace Dimsav\Backup;

class Project {

    private $config;

    private $name;
    private $paths = array();
    private $excludes = array();
    private $password;

    private $dbHost;
    private $dbPort;
    private $dbName;
    private $dbUsername;
    private $dbPassword;

    private $generatedFiles = array();

    public function __construct(Config $config, $projectName)
    {
        $this->config = $config;

        $this->name     = $projectName;
        $this->paths    = (array) $this->getConfig('paths');
        $this->excludes = (array) $this->getConfig('excludes');
        $this->password = $this->getConfig('password');
        $this->dbName   = $this->getConfig("database.name");

        if ($this->dbName)
        {
            $this->dbHost     = $this->getConfig('database.host');
            $this->dbPort     = $this->getConfig('database.port');
            $this->dbUsername = $this->getConfig('database.username');
            $this->dbPassword = $this->getConfig('database.password');
        }
    }

    public function getBackupFile($extension = '')
    {
        $timestamp = date($this->config->get('app.timestamp_prefix', "Y.m.d.H.i."));

        $file = $this->config->get('app.backups_dir');
        $file.= "/{$this->name}/{$timestamp}{$this->name}";
        $file.= $extension ? ".{$extension}" : '';
        return $file;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPaths()
    {
        return $this->paths;
    }

    public function getExcludes()
    {
        return $this->excludes;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getDbHost()
    {
        return $this->dbHost;
    }

    public function getDbPort()
    {
        return $this->dbPort;
    }

    public function getDbName()
    {
        return $this->dbName;
    }

    public function getDbUsername()
    {
        return $this->dbUsername;
    }

    public function getDbPassword()
    {
        return $this->dbPassword;
    }

    public function addToGeneratedFiles(array $files)
    {
        $this->generatedFiles = array_merge($this->generatedFiles, $files);
    }

    private function getConfig($parameter)
    {
        return $this->config->get(
            "projects.projects.$this->name.$parameter",
            $this->config->get("projects.default.$parameter")
        );
    }

}