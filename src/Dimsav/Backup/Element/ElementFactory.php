<?php namespace Dimsav\Backup\Element;

use Dimsav\Backup\Element\Drivers\Directory;
use Dimsav\Backup\Element\Drivers as Drivers;
use Dimsav\Backup\Element\Exceptions\InvalidProjectDriverException;
use Dimsav\Backup\Element\Exceptions\InvalidProjectNameException;
use Dimsav\Backup\Shell;
use Dimsav\UnixZipper;

class ElementFactory
{
    private $config;
    private $supportedDrivers = array('database', 'directories');

    public function __construct($projectsConfig)
    {
        $this->config = $projectsConfig;
    }

    public function make($projectName, $driver, $elementName)
    {
        $this->validate($projectName, $driver, $elementName);
        return $this->createDriver($projectName, $driver, $elementName);
    }

    public function makeAll($projectName)
    {
        $elements = array();
        $this->validateProject($projectName);

        foreach ($this->supportedDrivers as $driver)
        {
            try {
                $this->validateDriverInProject($projectName, $driver);
                $elementNames = $this->getElementNames($projectName, $driver);
                foreach ($elementNames as $elementName)
                {
                    $elements[] = $this->make($projectName, $driver, $elementName);
                }
            } catch (InvalidProjectDriverException $e) {}
        }
        return $elements;
    }

    private function getElementNames($projectName, $driver)
    {
        return array_keys($this->config[$projectName][$driver]);
    }

    private function validate($projectName, $driver, $elementName)
    {
        $this->validateProject($projectName);

        if ( ! in_array($driver, $this->supportedDrivers))
        {
            throw new \InvalidArgumentException("The driver '$driver' is not supported. Check your settings in project '$projectName'.");
        }

        $this->validateDriverInProject($projectName, $driver);

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
        if ($driver == 'database')
        {
            $config = $this->getDatabaseConfig($projectName, $elementName);
            $class = "Dimsav\\Backup\\Element\\Drivers\\".ucfirst($config['driver']);
            return new $class($config, new Shell());
        }
        else // directories
        {
            list($root, $config) = $this->getDirectoryConfig($projectName, $elementName);
            return new Directory($root, $config, new UnixZipper);
        }
    }

    private function getDatabaseConfig($projectName, $elementName)
    {        
        $config =  $this->config[$projectName]['database'][$elementName];

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

    /**
     * @param $projectName
     * @throws \InvalidArgumentException
     */
    private function validateProject($projectName)
    {
        if (!isset($this->config[$projectName])) {
            throw new InvalidProjectNameException("The project '$projectName' was not found.");
        }
    }

    /**
     * @param $projectName
     * @param $driver
     * @throws Exceptions\InvalidProjectDriverException
     */
    private function validateDriverInProject($projectName, $driver)
    {
        if (!isset($this->config[$projectName][$driver])) {
            throw new InvalidProjectDriverException("The project '$projectName' has no driver '$driver' set.");
        }
    }
}
