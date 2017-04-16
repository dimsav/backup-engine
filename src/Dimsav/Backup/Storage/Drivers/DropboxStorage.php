<?php namespace Dimsav\Backup\Storage\Drivers;

use Dimsav\Backup\Storage\Storage;

use League\Flysystem\Adapter\Dropbox;
use Dropbox\Client;
use League\Flysystem\Filesystem as Flysystem;

class DropboxStorage implements Storage
{
    /**
     * Test fitness of visitor.
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type) == 'dropbox';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        $client = new Client($config['token'], $config['app']);
        return new Flysystem(new Dropbox($client, $config['root']));
    }
}
