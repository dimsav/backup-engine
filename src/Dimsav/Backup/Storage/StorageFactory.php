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

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function make($name)
    {
        $this->validate($name);

        if ( ! isset($this->storages[$name]))
        {
            $this->storages[$name] = $this->createStorage($name);
        }
        return $this->storages[$name];
    }

    private function validate($name)
    {
        if ( ! isset($this->config[$name]))
        {
            throw new StorageNotFoundException;
        }
        elseif ( ! isset($this->config[$name]['driver']))
        {
            throw new StorageDriverNotDefinedException;
        }
    }

    private function createStorage($name)
    {
        switch ($this->getDriver($name))
        {
            case 'dropbox':
                return new Dropbox($this->getDriverConfig($name), new Shell());
            case 'local':
                return new Local($this->getDriverConfig($name));
        }
        throw new StorageDriverNotSupportedException;
    }

    private function getDriverConfig($name)
    {
        $config = $this->config[$name];
        $config['name'] = $name;
        return $config;
    }

    private function getDriver($name)
    {
        return $this->config[$name]['driver'];
    }
}
