<?php namespace Dimsav\Backup\Storage\Drivers;

use Dimsav\Backup\Storage\Storage;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem as Flysystem;

class LocalStorage implements Storage {

    /**
     * return name of the storage
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type) == 'local';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        return new Flysystem(new Local($config['destination']));
    }
}