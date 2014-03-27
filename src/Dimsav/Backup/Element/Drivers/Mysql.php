<?php namespace Dimsav\Backup\Element\Drivers;

use Dimsav\Backup\Element\AbstractElement;

class Mysql extends AbstractElement
{

//    private $database;
//    private $host;
//    private $port;
//    private $username;
//    private $password;

    function __construct(array $config)
    {
        $this->validate($config);
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

    /**
     * Saves the files into the extraction directory
     */
    public function extract()
    {
        // TODO: Implement extract() method.
    }

    /**
     * Returns the absolute path of the files created upon extract()
     */
    public function getExtractedFiles()
    {
        // TODO: Implement getExtractedFiles() method.
    }
}