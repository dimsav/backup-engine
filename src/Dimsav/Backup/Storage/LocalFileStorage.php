<?php namespace Dimsav\Backup\Storage;

use Dimsav\Backup\Project\Location;

class LocalFileStorage extends AbstractStorage implements StorageInterface {

    const DRIVER = 'local_file';

    public function store(Location $file)
    {
        // TODO: Implement store() method.
    }
}