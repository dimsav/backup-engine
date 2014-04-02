<?php namespace Dimsav\Backup\Storage\Drivers;

use Dimsav\Backup\Shell;
use Dimsav\Backup\Storage\Storage;


class Dropbox implements Storage
{
    private $destination;
    private $name;
    protected $config;

    /**
     * @var Shell
     */
    private $shell;

    public function __construct(array $config, Shell $shell)
    {
        $this->validate($config);
        $this->name = $config['name'];
        $this->shell = $shell;
        $this->destination = isset($config['destination']) ? $config['destination'] : '/';
    }

    public function store($file)
    {
        $this->validateFile($file);
        $this->setup();
        $this->shell->exec($this->getCommand($file));
    }

    public function getCommand($file)
    {
        return $this->getScript().' -f '.$this->getConfigFile()." upload $file " . $this->destination;
    }

    private function setup()
    {
        if ( ! is_dir($this->getTokenDir()))
        {
            mkdir($this->getTokenDir());
        }
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
        if ( ! $this->hasTokenFile($config))
        {
            throw new \InvalidArgumentException("The dropbox storage '{$config['name']}' has not a token set.");
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

    public function hasTokenFile($config)
    {
        return is_file($this->getTokenDir().'/.dropbox_'.$config['name']);
    }

    private function getTokenDir()
    {
        return realpath(__DIR__.'/../../../../../config').'/tokens';
    }

    private function getScript()
    {
        return realpath(__DIR__.'/../../../../../bin/dropbox_uploader.sh');
    }

    private function getConfigFile()
    {
        return $this->getTokenDir().'/.dropbox_'.$this->name;
    }
}
