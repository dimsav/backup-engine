<?php namespace Dimsav\Backup\Storage;

use Dimsav\Backup\Project\Element\Directory;

interface Storage {

    public function __constructor($config);
    public function store(Directory $file);

} 