<?php namespace Dimsav\Backup\Storage\Drivers;

use Dimsav\Backup\Shell;
use Dimsav\Backup\Storage\Exceptions\InvalidStorageException;
use Dimsav\Backup\Storage\Exceptions\TokenNotSetException;
use Dimsav\Backup\Storage\Storage;


class Dropbox implements Storage
{
    private $destination;
    private $name;
    private $username;
    private $clean;

    /**
     * @var Shell
     */
    private $shell;

    public function __construct(array $config, Shell $shell)
    {
        $this->config = $config;
        $this->shell = $shell;
        $this->setProperties($config);
    }

    public function store($file, $projectName = null)
    {
        $file = new \SplFileInfo($file);
        $prefix = ($file->getExtension() == "sql") ? "sql" : "files";

        $this->validate();
        $this->validateFile($file);
        $this->shell->exec($this->getCommand($file, $projectName, $prefix));

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

    public function getCommand($file, $projectName, $prefix)
    {

        $destination = $projectName ? $this->destination . "/$projectName". "/". $prefix : $this->destination ."/". $prefix;
        $destination .= substr($destination, -1, 1) == '/' ? basename($file) : '/' . basename($file);
        return $this->getScript().' -f '.$this->getConfigFile()." upload $file " . $destination;
    }

    public function getFileListCommand($projectName, $prefix)
    {
        return $this->getScript().' -f '.$this->getConfigFile()." list ". $this->destination . "/$projectName"."/".$prefix;
    }

    private function removeCommand($projectName, $file, $prefix)
    {
        return $this->getScript().' -f '.$this->getConfigFile()." delete ". $this->destination . "/$projectName"."/".$prefix."/".$file;
    }

    /**
     * @return void
     * @throws \Dimsav\Backup\Storage\Exceptions\TokenNotSetException
     * @throws \Dimsav\Backup\Storage\Exceptions\InvalidStorageException
     */
    public function validate()
    {
        if ( ! $this->name)
        {
            throw new InvalidStorageException("The name for the 'dropbox' storage is not set.");
        }
        elseif ( ! $this->username)
        {
            throw new InvalidStorageException("The local storage '{$this->name}' has no username set.");
        }
        elseif ( ! $this->hasTokenFile($this->config))
        {
            throw new TokenNotSetException("\n    The dropbox storage '{$this->name}' for '{$this->username}' has not a token set.\n".
            "    Please run the command below and follow the instructions to enable dropbox access:\n".
            "    \"bin/dropbox_uploader.sh -f ". $this->getTokenFile() . '"');
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

    public function hasTokenFile()
    {
        return is_file($this->getTokenFile());
    }

    private function getTokenFile()
    {
        return $this->getTokenDir()."/.dropbox_{$this->name}";
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

    private function setProperties(array $config)
    {
        $this->name = isset($config['name']) ? $config['name'] : null;
        $this->username = isset($this->config['username']) ? $this->config['username'] : null;
        $this->destination = isset($config['destination']) ? $config['destination'] : '/';
        $this->clean = isset($config['clean']) ? $config['clean'] : '365 days';
    }

    private function parseFiles($data)
    {
        $deleteList = array();
        $data = explode("[F]", $data);
        $data = array_slice($data, 1);

        foreach ($data as $file) {

            preg_match('([\s-]\S{1,})', ltrim($file), $filename);
            $filename = ltrim($filename[0]);

            preg_match('([\s-]\S{1,16})', ltrim($file), $result);
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
