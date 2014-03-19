<?php

namespace Dimsav\Backup\Storage;

use Dimsav\Backup\Project\Location;

class DropboxStorage implements StorageInterface
{
    const TYPE = 'dropbox';

    private $alias;
    private $username;
    private $password;
    private $destination;

    public function __construct(array $data)
    {
        $this->alias = isset($data['alias']) ? $data['alias'] : null;
        $this->username = isset($data['username']) ? $data['username'] : null;
        $this->password = isset($data['password']) ? $data['password'] : null;
        $this->destination = isset($data['destination']) ? $data['destination'] : null;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function getType()
    {
        return static::TYPE;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function store(Location $file)
    {
        // TODO: Implement store() method.
    }
}
