<?php namespace Dimsav\Backup\Storage;

class StorageManager
{
    private $config;

    function __construct($config, StorageFactory $factory)
    {
        $this->config = $config;
        $this->factory = $factory;
    }

    public function storage($name)
    {
        return $this->factory->make($this->config[$name]);
    }
}
