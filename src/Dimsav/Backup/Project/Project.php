<?php namespace Dimsav\Backup\Project;

use Dimsav\Backup\Storage\StorageInterface;
use Illuminate\Support\Collection;
use Dimsav\Backup\Project\Element\Database;
use Dimsav\Backup\Project\Element\Directory;

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
     * @var Directory[]
     */
    private $paths = array();

    /**
     * @var Directory[]
     */
    private $excludes = array();

    private $storageNames = array();

    /**
     * Associative array containing the storages used for this project.
     * The keys represent the storage aliases.
     *
     * @var StorageInterface[]
     */
    private $storages = array();
    private $password;

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
     * @return Directory[]
     */
    public function getPaths()
    {
        return $this->paths;
    }

    public function addExclude(Directory $exclude)
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

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param array $storageNames
     */
    public function setStorageNames($storageNames)
    {
        $this->storageNames = $storageNames;
    }

    /**
     * @return array
     */
    public function getStorageNames()
    {
        return $this->storageNames;
    }

}
