<?php namespace Dimsav\Backup\Project;

use Dimsav\Backup\Element\Element;
use Dimsav\Backup\Storage\Storage;

class Project {

    private $storages = array();
    private $name;

    /**
     * @var Element[]
     */
    private $elements = array();

    public function setStorages($storages)
    {
        $this->storages = $storages;
    }

    public function getStorages()
    {
        return $this->storages;
    }

    public function setElements($elements)
    {
        $this->elements = $elements;
    }

    public function getElements()
    {
        return $this->elements;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}
