<?php namespace Dimsav\Backup\Project;

use Dimsav\Backup\Project\Element\Element;
use Dimsav\Backup\Storage\Storage;
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
     * @var Storage[]
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

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function addStorage(Storage $storage)
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

    // Todo: test
    public function extract()
    {
        foreach ($this->getElements() as $element)
        {
            $element->extract();
            $this->addToExtracted($element->getExtracted());
        }
    }

    // Todo: test
    public function store()
    {
        foreach ($this->storages as $storage)
        {
            foreach ($this->getExtracted() as $file)
            $storage->store($file);
        }
    }
}
