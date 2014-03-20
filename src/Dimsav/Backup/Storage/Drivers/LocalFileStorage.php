<?php namespace Dimsav\Backup\Storage\Drivers;

use Dimsav\Backup\Project\Location,
    Dimsav\Backup\Storage\AbstractStorage,
    Dimsav\Backup\Storage\StorageInterface;



class LocalFileStorage extends AbstractStorage implements StorageInterface {

    const DRIVER = 'local_file';

    public function store(Location $file)
    {
        // TODO: Implement store() method.
    }
}