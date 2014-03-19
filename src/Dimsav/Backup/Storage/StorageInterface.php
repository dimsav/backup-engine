<?php namespace Dimsav\Backup\Storage;

use Dimsav\Backup\Project\Location;

interface StorageInterface {

    public function getAlias();
    public function getType();
    public function getUsername();
    public function getPassword();
    public function getDestination();
    public function store(Location $file);

} 