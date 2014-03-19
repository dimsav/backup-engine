<?php namespace Dimsav\Backup\Project;

use Dimsav\Backup\Storage\StorageInterface;
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

    /**
     * Associative array containing the storages used for this project.
     * The keys represent the storage aliases.
     *
     * @var StorageInterface[]
     */
    private $storages = array();

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

    public function addStorage(StorageInterface $storage)
    {
        $this->storages[$storage->getAlias()] = $storage;
    }

    public function getStorages()
    {
        return $this->storages;
    }
}