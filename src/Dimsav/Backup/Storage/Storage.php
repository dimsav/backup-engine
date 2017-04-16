<?php namespace Dimsav\Backup\Storage;

use Dimsav\Backup\Storage\Exceptions\InvalidStorageException;

interface Storage {

    /**
     * return name of the storage
     * @param $type
     * @return bool
     */
    public function handles($type);

    /**
     * @param array $config
     * @return \League\Flysystem\Filesystem
     */
    public function get(array $config);

} 