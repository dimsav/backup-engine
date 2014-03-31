<?php namespace Dimsav\Backup\Storage;

use Dimsav\Backup\Storage\Drivers\Dropbox,
    Dimsav\Backup\Storage\Drivers\Local;

class StorageFactory
{
    public function make($config)
    {
        return $this->createStorage($config['driver'], $config);
    }

    private function createStorage($driver, $config)
    {
        switch ($driver)
        {
            case 'dropbox':
                return new Dropbox($config);
            case 'local':
                return new Local($config);
        }
        throw new \InvalidArgumentException('Invalid storage driver');
    }
}
