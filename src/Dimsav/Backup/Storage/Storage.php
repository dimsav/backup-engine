<?php namespace Dimsav\Backup\Storage;

interface Storage {

    public function getAlias();
    public function getDestination();
    public function store($file);

} 