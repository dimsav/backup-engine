<?php namespace Dimsav\Backup\Project;

use Dimsav\Backup\Element\Element;
use Dimsav\Backup\Storage\Storage;

class Project {

    private $storages = array();

    /**
     * @var Element[]
     */
    private $elements = array();

    public function addStorage(Storage $storage)
    {
        $this->storages[] = $storage;
    }

    public function getStorages()
    {
        return $this->storages;
    }

    public function addElement(Element $element)
    {
        $this->elements[] = $element;
    }

    public function getElements()
    {
        return $this->elements;
    }

//    public function getBackupFile($extension = '')
//    {
//        $timestamp = date($this->config->get('app.timestamp_prefix', "Y.m.d.H.i."));
//
//        $file = $this->config->get('app.backups_dir');
//        $file.= "/{$this->name}/{$timestamp}{$this->name}";
//        $file.= $extension ? ".{$extension}" : '';
//        return $file;
//    }

}
