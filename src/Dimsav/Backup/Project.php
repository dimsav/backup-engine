<?php namespace Dimsav\Backup;

class Project {

    /** @var LoggerSingleton  */
    private $log;

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
        $this->log = LoggerSingleton::getInstance();
        $this->projectName = $projectName;
        $this->determine();
    }

    private function determine()
    {
        $this->paths      = (array) $this->getValueFromConfig('paths');
        $this->excludes   = (array) $this->getValueFromConfig('excludes');
        $this->password   = $this->getValueFromConfig('password');

        $this->dbName     = Config::get("projects.projects.$this->projectName.database.name");

        if ($this->dbName)
        {
            $this->dbHost     = $this->getValueFromConfig('database.host');
            $this->dbUsername = $this->getValueFromConfig('database.username');
            $this->dbPassword = $this->getValueFromConfig('database.password');
        }
    }

    private function getValueFromConfig($parameter)
    {
        return Config::get(
            "projects.projects.$this->projectName.$parameter",
            Config::get("projects.default.$parameter")
        );
    }

}