<?php namespace Dimsav\Backup;

use Dimsav\UnixZipper;

class Project {

    /** @var LoggerSingleton  */
    private $log;
    /** @var  \Dimsav\UnixZipper */
    private $zipper;

    private $projectName;
    private $paths = array();
    private $excludes = array();
    private $password;
    private $dbName;
    private $dbHost;
    private $dbUsername;
    private $dbPassword;

    private $generatedFiles = array();

    public function __construct($projectName)
    {
        $this->log = LoggerSingleton::getInstance();
        $this->zipper = new UnixZipper();
        $this->projectName = $projectName;
        $this->determine();
    }

    public function compressFiles()
    {
        $this->log->addInfo("Compressing files of project $this->projectName");
        foreach ($this->paths as $path)
        {
            $this->zipper->add($path);
        }
        foreach ($this->excludes as $exclude)
        {
            $this->zipper->exclude($exclude);
        }
        if ($this->password)
        {
            $this->zipper->setPassword($this->password);
        }
        $timestamp = date(Config::get('app.timestamp_prefix', "Y.m.d.H.i."));
        $this->zipper->setDestination(Config::get('app.backups_dir')."/{$timestamp}{$this->projectName}.zip");
        $this->zipper->compress();
        $this->addToGeneratedFiles($this->zipper->getFiles());
    }

    private function addToGeneratedFiles($filesArray)
    {
        $this->generatedFiles = array_merge($this->generatedFiles, $filesArray);
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