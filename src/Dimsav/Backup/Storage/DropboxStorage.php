<?php

namespace Dimsav\Backup\Storage;

use Dimsav\Backup\Project\Location;

class DropboxStorage extends AbstractStorage implements StorageInterface, PasswordProtectedStorage
{
    const TYPE = 'dropbox';

    private $username;
    private $password;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->username = isset($data['username']) ? $data['username'] : null;
        $this->password = isset($data['password']) ? $data['password'] : null;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function store(Location $file)
    {
        // TODO: Implement store() method.
    }
}
