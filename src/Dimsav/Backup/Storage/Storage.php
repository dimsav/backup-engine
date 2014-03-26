<?php namespace Dimsav\Backup\Storage;

use Dimsav\Backup\Project\Element\Directory;

interface Storage {

    public function getAlias();
    public function getDestination();
    public function store(Directory $file);

} 