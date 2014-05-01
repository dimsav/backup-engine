<?php namespace Dimsav\Backup\Element;

use Dimsav\Backup\Element\Drivers\Directory;
use Dimsav\Backup\Element\Drivers\Mysql;
use Dimsav\Backup\Shell;
use Dimsav\UnixZipper;

class ElementFactory
{
    private $config;
    private $supportedDrivers = array('mysql', 'directories');

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function make($projectName, $driver, $elementName)
    {
        $this->validate($projectName, $driver, $elementName);
        return $this->createDriver($projectName, $driver, $elementName);
    }

    public function getDrivers()
    {
        return $this->supportedDrivers;
    }

    private function validate($projectName, $driver, $elementName)
    {

        if ( ! isset($this->config[$projectName]))
        {
            throw new \InvalidArgumentException("The project '$projectName' was not found.");
        }

        if ( ! in_array($driver, $this->supportedDrivers))
        {
            throw new \InvalidArgumentException("The driver '$driver' is not supported. Check your settings in project '$projectName'.");
        }

        if ( ! isset($this->config[$projectName][$driver]))
        {
            throw new \InvalidArgumentException("The project '$projectName' has no driver '$driver' set.");
        }

        if ( ! isset($this->config[$projectName][$driver][$elementName]))
        {
            throw new \InvalidArgumentException("The project '$projectName' has no driver '$driver' named '$elementName'.");
        }

        if ($driver == 'directories')
        {
            $this->validateDirectories($projectName);
        }

    }

    private function validateDirectories($projectName)
    {
        if ( ! isset($this->config[$projectName]['root_dir']))
        {
            throw new \InvalidArgumentException(
                "The project '$projectName' has no root_dir set. Please set it to backup project files");
        }
    }

    private function createDriver($projectName, $driver, $elementName)
    {
        switch ($driver) {
            case 'mysql':

                $config = $this->getMysqlConfig($projectName, $elementName);
                return new Mysql($config, new Shell());

            case 'directories':

                list($root, $config) = $this->getDirectoryConfig($projectName, $elementName);
                return new Directory($root, $config, new UnixZipper);
        }
    }

    private function getMysqlConfig($projectName, $elementName)
    {
        $config =  $this->config[$projectName]['mysql'][$elementName];

        if (!isset($config['database'])) {
            $config['database'] = $elementName;
        }
        return $config;
    }

    private function getDirectoryConfig($projectName, $elementName)
    {
        $rootDir = $this->config[$projectName]['root_dir'];
        $config =  $this->config[$projectName]['directories'][$elementName];

        if (is_array($config) && !isset($config['directory'])) {
            $config['directory'] = $elementName;
        }
        return array($rootDir, $config);
    }
}
