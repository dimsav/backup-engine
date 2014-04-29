<?php namespace Dimsav\Backup\Storage\Drivers;

use Dimsav\Backup\Storage\Storage;

class Local implements Storage {

    private $name;
    private $destination;

    public function __construct(array $config)
    {
        $this->validate($config);
        $this->name = $config['name'];
        $this->destination = realpath($config['destination']);
    }

    public function store($file, $projectName = null)
    {
        $this->validateFile($file);

        $exportDir = $this->destination . '/' . $projectName;
        if ($projectName && ! is_dir($exportDir))
        {
            mkdir($exportDir);
        }

        copy($file, $exportDir . '/' . basename($file));
    }

    private function validate($config)
    {
        if ( ! isset($config['name']))
        {
            throw new \InvalidArgumentException('The name for the local storage is not set.');
        }
        elseif ( ! isset($config['destination']))
        {
            throw new \InvalidArgumentException("The local storage '{$config["name"]}' has no destination set.");
        }
        elseif ( ! is_dir($config['destination']))
        {
            throw new \InvalidArgumentException("The destination of the local storage '{$config["name"]}' is not a valid directory.");
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