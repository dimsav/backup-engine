<?php namespace Dimsav\Backup\Project;

use Dimsav\Backup\Project\Element\Element;
use Dimsav\Backup\Storage\StorageInterface;
use Dimsav\Backup\Project\Element\Directory;

class Project {

    /**
     * @var string
     */
    private $name;

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

    /**
     * @var Element[]
     */
    private $elements = array();

    public function __construct($name)
    {
        $this->name = $name;
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

    public function addElement(Element $element)
    {
        $this->elements[] = $element;
    }

    public function getElements()
    {
        return $this->elements;
    }
}
