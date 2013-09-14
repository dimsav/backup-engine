<?php namespace Dimsav\Backup;

class Project {

    private $projectName;
    private $paths = array();
    private $excludes = array();
    private $password;
    private $dbName;
    private $dbHost;
    private $dbUsername;
    private $dbPassword;

    public function __construct($projectName)
    {
        $this->projectName = $projectName;

        $this->paths      = (array) $this->getValueFromConfig('paths');
        $this->excludes   = (array) $this->getValueFromConfig('excludes');
        $this->password   = $this->getValueFromConfig('password');

        $this->dbName     = Config::get("projects.projects.$projectName.database.name");

        if ($this->dbName)
        {
            $this->dbHost     = $this->getValueFromConfig('database.host');
            $this->dbUsername = $this->getValueFromConfig('database.username');
            $this->dbPassword = $this->getValueFromConfig('database.password');
        }
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

    public function getDbName()
    {
        return $this->dbName;
    }

    public function getDbHost()
    {
        return $this->dbHost;
    }

    public function getDbUsername()
    {
        return $this->dbUsername;
    }

    public function getDbPassword()
    {
        return $this->dbPassword;
    }

    private function getValueFromConfig($parameter)
    {
        return Config::get(
            "projects.projects.$this->projectName.$parameter",
            Config::get("projects.default.$parameter")
        );
    }

}