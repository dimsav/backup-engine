<?php namespace Dimsav\Backup\Storage\Drivers;

use Dimsav\Backup\Storage\Storage;

class Dropbox implements Storage
{
    private $username;
    private $password;
    private $destination;
    private $name;

    protected $config;

    public function __construct(array $config)
    {
        $this->validate($config);
        $this->name = $config['name'];
    }

    public function store($file)
    {
        $this->validateFile($file);
        // TODO: Implement store() method.
    }

    private function validate($config)
    {
        if ( ! isset($config['name']))
        {
            throw new \InvalidArgumentException("The name for the 'dropbox' storage is not set.");
        }
        elseif ( ! isset($config['username']))
        {
            throw new \InvalidArgumentException("The local storage '{$config['name']}' has no username set.");
        }
        elseif ( ! isset($config['password']))
        {
            throw new \InvalidArgumentException("The local storage '{$config['name']}' has no password set.");
        }
    }

    /**
     * @param $file
     * @throws \InvalidArgumentException
     */
    private function validateFile($file)
    {
        if ( ! is_file($file)) {
            throw new \InvalidArgumentException("Dropbox storage '{$this->name}' could not find the file '$file'.");
        }
    }
}
