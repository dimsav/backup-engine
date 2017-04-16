<?php namespace Dimsav\Backup\Storage\Drivers;

use Dimsav\Backup\Storage\Storage;

use League\Flysystem\Adapter\Sftp;
use League\Flysystem\Filesystem as Flysystem;


class SftpStorage implements Storage
{
     /**
     * return name of the storage
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type) == 'sftp';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        return new Flysystem(new Sftp($config));
    }
}
