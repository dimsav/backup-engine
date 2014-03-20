<?php

namespace Dimsav\Backup\Storage;

use Illuminate\Container\Container;

class StorageFactory
{

    /**
     * @var Container
     */
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function make($config, $name = null)
    {
        return $this->createStorage($config['driver'], $config);
    }

    private function createStorage($driver, $config)
    {
        switch ($driver)
        {
            case 'dropbox':
                return new DropboxStorage($config);
            case 'local':
                return new LocalFileStorage($config);
        }
        throw new \InvalidArgumentException('Invalid storage driver');
    }
}
