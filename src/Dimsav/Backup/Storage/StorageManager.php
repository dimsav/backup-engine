<?php namespace Dimsav\Backup\Storage;

class StorageManager
{
    private $config;

    function __construct($config, StorageFactory $factory)
    {
        $this->config = $this->parseConfiguration($config);
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

    private function parseConfiguration($config)
    {
        if ( ! isset($config['storages']) || ! is_array($config['storages']))
        {
            throw new \InvalidArgumentException('Storages array is not in configuration');
        }
        return $config['storages'];
    }
}
