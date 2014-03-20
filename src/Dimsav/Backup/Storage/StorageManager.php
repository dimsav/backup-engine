<?php namespace Dimsav\Backup\Storage;

class StorageManager
{
    private $config;

    function __construct($config, StorageFactory $factory)
    {
        $this->config = $config['storages'];
        $this->factory = $factory;
    }

    public function storage($name)
    {
        if ( ! isset($this->config[$name]))
        {
            throw new \InvalidArgumentException("Invalid storage '$name'");
        }
        return $this->factory->make($this->config[$name]);
    }
}
