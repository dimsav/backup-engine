<?php namespace Dimsav\Backup\Element\Drivers;

use Dimsav\Backup\Element\AbstractElement;
use Dimsav\Backup\Element\Exceptions\ExtractionFailureException;
use Dimsav\Backup\Shell;

class Pgsql extends AbstractElement
{

    private $database;
    private $host;
    private $port;
    private $username;
    private $password;
    private $extractedFile;
    /**
     * @var \Dimsav\Backup\Shell
     */
    private $shell;

    function __construct(array $config, Shell $shell)
    {
        $this->validate($config);
        $this->database = $config['database'];
        $this->host = $config['host'];
        $this->port = $config['port'];
        $this->username = $config['username'];
        $this->password = $config['password'];

        $this->shell = $shell;
    }

    private function validate($config)
    {
        $fields = array('database', 'host', 'port', 'username', 'password');
        foreach ($fields as $field)
        {
            if ( ! isset($config[$field]))
            {
                throw new \InvalidArgumentException("The field '$field' is not set.");
            }
        }
    }

    public function setExtractionDir($dir)
    {
        parent::setExtractionDir($dir);
        $this->extractedFile = date("Y-m-d_H-i-s")."_{$this->database}.sql";
    }

    /**
     * Saves the files into the extraction directory
     */
    public function backup()
    {
        $this->shell->exec($this->getCommand());
        if ($this->shell->getStatusCode() != 0)
        {
            throw new ExtractionFailureException(
                "The backup of database '{$this->database}' could not be created. " .
                $this->shell->getOutput());
        }
        $this->extractedFiles[] = $this->extractedFile;
    }

    private function getCommand()
    {

        $command = sprintf('PGPASSWORD=%s pg_dump --host=%s --port=%s --username=%s %s -f %s',
            escapeshellarg($this->password),
            escapeshellarg($this->host),
            escapeshellarg($this->port),
            escapeshellarg($this->username),
            escapeshellarg($this->database),
            escapeshellarg($this->extractionDir. "/".$this->extractedFile)
        );
        return $command;
    }

    /**
     * Returns the absolute path of the files created upon extract()
     */
    public function getExtractedFiles()
    {
        return $this->extractedFiles;
    }
}