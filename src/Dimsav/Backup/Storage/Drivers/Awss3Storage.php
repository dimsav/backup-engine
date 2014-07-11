<?php namespace Dimsav\Backup\Storage\Drivers;

use Dimsav\Backup\Storage\Storage;

use League\Flysystem\Adapter\AwsS3;
use Aws\S3\S3Client;
use League\Flysystem\Filesystem as Flysystem;

class Awss3Storage implements Storage
{
    /**
     * @param $type
     * @return bool
     */
    public function handles($type)
    {
        return strtolower($type) == 'awss3';
    }

    /**
     * @param array $config
     * @return \League\Flysystem\Filesystem
     */
    public function get(array $config)
    {
        $client = S3Client::factory([
            'key' => $config['key'],
            'secret' => $config['secret'],
            'region' => $config['region'],
        ]);

        return new Flysystem(new AwsS3($client, $config['bucket'], $config['root']));
    }
}
