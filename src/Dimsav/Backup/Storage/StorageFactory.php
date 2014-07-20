<?php namespace Dimsav\Backup\Storage;

use Dimsav\Backup\Shell;
use Dimsav\Backup\Storage\Drivers\Dropbox,
    Dimsav\Backup\Storage\Drivers\Local;
use Dimsav\Backup\Storage\Exceptions\StorageDriverNotDefinedException;
use Dimsav\Backup\Storage\Exceptions\StorageDriverNotSupportedException;
use Dimsav\Backup\Storage\Exceptions\StorageNotFoundException;
use Dimsav\Backup\Storage\Exceptions\StoragesNotConfiguredException;

class StorageFactory
{

    private $config;
    private $storages = array();

    public function __construct(array $fullConfig)
    {
        $this->config = $fullConfig;
        $this->validate();
    }

    public function makeAll()
    {
        $storages = array();
        foreach ($this->config['storages'] as $storageName => $storageConfig)
        {
            $storages[$storageName] = $this->make($storageName);
        }
        return $storages;
    }

    public function make($storageName)
    {
        $this->validateStorage($storageName);

        if ( ! isset($this->storages[$storageName]))
        {
            $this->storages[$storageName] = $this->createStorage($storageName);
        }
        return $this->storages[$storageName];
    }

    public function makeByProjectName($projectName)
    {
        $storages = array();
        $storageNames = (array) $this->getProjectStorageNames($projectName);
        foreach ($storageNames as $storageName)
        {
            $storages[] = $this->make($storageName);
        }
        return $storages;
    }

    private function getProjectStorageNames($projectName)
    {
        $this->validateProject($projectName);
        return $this->config['projects'][$projectName]['storages'];
    }

    private function validate()
    {
        if ( ! isset($this->config['storages']))
        {
            throw new StoragesNotConfiguredException;
        }
    }

    private function validateStorage($storageName)
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
                return new Local($this->getDriverConfig($storageName), new Shell());
        }
        throw new StorageDriverNotSupportedException;
    }

    private function getDriverConfig($storageName)
    {
        $config = $this->config['storages'][$storageName];
        $config['name'] = $storageName;
        return $config;
    }

    private function getDriver($storageName)
    {
        return $this->config['storages'][$storageName]['driver'];
    }

    /**
     * @param $projectName
     * @throws Exceptions\StoragesNotConfiguredException
     */
    private function validateProject($projectName)
    {
        if ((!isset($this->config['projects'][$projectName]['storages'])) || empty($this->config['projects'][$projectName]['storages'])) {
            throw new StoragesNotConfiguredException("The project '$projectName' has no storages defined.");
        }
    }
}
