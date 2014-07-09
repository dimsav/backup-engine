<?php namespace Dimsav\Backup\Storage\Drivers;

use Dimsav\Backup\Storage\Storage;

use League\Flysystem\Adapter\Rackspace as RackspaceAdapter;
use League\Flysystem\Filesystem as Flysystem;
use OpenCloud\OpenStack;

class RackspaceStorage implements Storage
{
    /**
     * return name of the storage
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type) == 'rackspace';
    }

    /**
     * @param array $config
     * @return Flysystem
     */
    public function get(array $config)
    {
        $client = new OpenStack($config['endpoint'], [
            'username' => $config['username'],
            'password' => $config['key'],
        ]);
        $container = $client->objectStoreService('cloudFiles', $config['zone'])->getContainer($config['container']);
        return new Flysystem(new RackspaceAdapter($container, $config['root']));
    }
}
