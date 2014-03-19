<?php namespace Dimsav\Backup\Storage;

use Dimsav\Backup\Project\Location;

interface StorageInterface {

    public function getAlias();
    public function getDriver();
    public function getDestination();
    public function store(Location $file);

} 