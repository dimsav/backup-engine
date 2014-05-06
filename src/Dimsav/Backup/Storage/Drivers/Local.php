<?php namespace Dimsav\Backup\Storage\Drivers;

use Dimsav\Backup\Storage\Exceptions\InvalidStorageException;
use Dimsav\Backup\Storage\Storage;

class Local implements Storage {

    private $name;
    private $destination;

    public function __construct(array $config)
    {
        $this->setProperties($config);
    }

    private function setProperties(array $config)
    {
        $this->name = isset($config['name']) ? $config['name'] : null;
        $this->destination = $this->getDestination($config);
    }

    private function getDestination($config)
    {
        $destination = isset($config['destination']) ? $config['destination'] : null;

        if ($destination !== null && ! is_dir($destination))
        {
            mkdir($destination, 0777, true);
        }
        return $destination !== null ? realpath($destination) : null;
    }

    /**
     * @param $file
     * @param null $projectName
     * @return mixed|void
     * @throws InvalidStorageException
     * @throws \InvalidArgumentException
     */
    public function store($file, $projectName = null)
    {
        $this->validate();
        $this->validateFile($file);

        $exportDir = $this->destination . '/' . $projectName;
        if ($projectName && ! is_dir($exportDir))
        {
            mkdir($exportDir, 0777, true);
        }

        // we don't want to move the file for the case we have more storages

        copy($file, $exportDir . '/' . basename($file));
    }

    /**
     * @return void
     * @throws InvalidStorageException
     */
    public function validate()
    {
        if ( ! $this->name)
        {
            throw new InvalidStorageException('The name for the local storage is not set.');
        }
        elseif ( $this->destination === null)
        {
            throw new InvalidStorageException("The local storage '{$this->name}' has no destination set.");
        }
    }

    /**
     * @param $file
     * @throws \InvalidArgumentException
     */
    private function validateFile($file)
    {
        if ( ! is_file($file)) {
            throw new \InvalidArgumentException("Local storage '{$this->name}' could not find the file '$file'.");
        }
    }
}