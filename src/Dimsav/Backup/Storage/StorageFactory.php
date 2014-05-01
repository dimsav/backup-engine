<?php namespace Dimsav\Backup\Storage;

use Dimsav\Backup\Shell;
use Dimsav\Backup\Storage\Drivers\Dropbox,
    Dimsav\Backup\Storage\Drivers\Local;
use Dimsav\Backup\Storage\Exceptions\StorageDriverNotDefinedException;
use Dimsav\Backup\Storage\Exceptions\StorageDriverNotSupportedException;
use Dimsav\Backup\Storage\Exceptions\StorageNotFoundException;

class StorageFactory
{

    private $config;
    private $storages = array();

    public function __construct(array $fullConfig)
    {
        $this->config = $fullConfig;
    }

    public function make($storageName)
    {
        $this->validate($storageName);

        if ( ! isset($this->storages[$storageName]))
        {
            $this->storages[$storageName] = $this->createStorage($storageName);
        }
        return $this->storages[$storageName];
    }

    private function validate($storageName)
    {
        if ( ! isset($this->config['storages'][$storageName]))
        {
            throw new StorageNotFoundException;
        }
        elseif ( ! isset($this->config['storages'][$storageName]['driver']))
        {
            throw new StorageDriverNotDefinedException;
        }
    }

    private function createStorage($storageName)
    {
        switch ($this->getDriver($storageName))
        {
            case 'dropbox':
                return new Dropbox($this->getDriverConfig($storageName), new Shell());
            case 'local':
                return new Local($this->getDriverConfig($storageName));
        }
        throw new StorageDriverNotSupportedException;
    }

    private function getDriverConfig($name)
    {
        $config = $this->config['storages'][$name];
        $config['name'] = $name;
        return $config;
    }

    private function getDriver($name)
    {
        return $this->config['storages'][$name]['driver'];
    }
}
