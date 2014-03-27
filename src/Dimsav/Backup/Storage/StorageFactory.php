<?php namespace Dimsav\Backup\Storage;

use Dimsav\Backup\Storage\Drivers\DropboxStorage,
    Dimsav\Backup\Storage\Drivers\LocalFileStorage;

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
                return new DropboxStorage($config);
            case 'local':
                return new LocalFileStorage($config);
        }
        throw new \InvalidArgumentException('Invalid storage driver');
    }
}
