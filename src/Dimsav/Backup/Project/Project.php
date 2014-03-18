<?php namespace Dimsav\Backup\Project;

use Illuminate\Support\Collection;

class Project {

    /**
     * @var string
     */
    private $name;

    /**
     * @var Database[]
     */
    private $databases = array();

    /**
     * @var Location[]
     */
    private $paths = array();

    /**
     * @var Location[]
     */
    private $excludes = array();

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function addDatabase(Database $database)
    {
        $this->databases[]= $database;
    }

    public function getDatabases()
    {
        return $this->databases;
    }

    public function addPath($path)
    {
        $this->paths[] = $path;
    }

    /**
     * @return \Dimsav\Backup\Project\Location[]
     */
    public function getPaths()
    {
        return $this->paths;
    }

    public function addExclude(Location $exclude)
    {
        $this->excludes[] = $exclude;
    }

    public function getExcludes()
    {
        return $this->excludes;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


}
