<?php namespace Dimsav\Backup\Storage\Drivers;

use Dimsav\Backup\Shell;
use Dimsav\Backup\Storage\Exceptions\InvalidStorageException;
use Dimsav\Backup\Storage\Storage;

class Local implements Storage {

    private $name;
    private $destination;
    private $clean;

    /**
     * @var Shell
     */
    private $shell;

    public function __construct(array $config, Shell $shell)
    {
        $this->shell = $shell;
        $this->setProperties($config);
    }

    private function setProperties(array $config)
    {
        $this->name = isset($config['name']) ? $config['name'] : null;
        $this->destination = $this->getDestination($config);
        $this->clean = isset($config['clean']) ? $config['clean'] : '365 days';
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

        $file = new \SplFileInfo($file);

        $prefix = ($file->getExtension() == "sql") ? "sql" : "files";

        $exportDir = $this->destination . '/' . $projectName. "/". $prefix;
        if ($projectName && ! is_dir($exportDir))
        {
            mkdir($exportDir, 0777, true);
        }

        // we don't want to move the file for the case we have more storages

        copy($file, $exportDir . '/' . basename($file));

        $this->cleanOldBackups($projectName, $prefix);
    }

    private function cleanOldBackups($projectName, $prefix)
    {
        //clean shell output
        $this->shell->cleanOutput();

        //clean old backups
        $this->shell->exec($this->getFileListCommand($projectName, $prefix));

        $deleteList = $this->parseFiles($this->shell->getOutput());

        foreach ($deleteList as $file) {
            $this->shell->exec($this->removeCommand($projectName, $file, $prefix));
        }
    }

    public function getFileListCommand($projectName, $prefix)
    {
        return "ls ". $this->destination . "/$projectName"."/".$prefix." | tr '\n' '\n' | sed 's/$/|||/g'";
    }

    private function removeCommand($projectName, $file, $prefix)
    {
        return " rm ". $this->destination . "/$projectName"."/".$prefix."/".$file;
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

    private function parseFiles($data)
    {
        $deleteList = array();
        $data = explode("|||", $data);
        array_pop($data);

        foreach ($data as $file) {

            $filename = $file;

            preg_match('(\S{1,16})', ltrim($file), $result);
            $date = explode("_", $result[0]);
            $time = str_replace("-",":",$date[1]);
            $date = $date[0];

            $datetime = new \DateTime($date.$time);
            $today = new \DateTime();

            $today->modify($this->clean);
            if ($datetime < $today) {
                $deleteList[] = $filename;
            }
        }

        return $deleteList;
    }
}