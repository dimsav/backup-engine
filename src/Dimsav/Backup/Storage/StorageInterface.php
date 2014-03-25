<?php namespace Dimsav\Backup\Storage;

use Dimsav\Backup\Project\Element\Directory;

interface StorageInterface {

    public function getAlias();
    public function getDestination();
    public function store(Directory $file);

} 