<?php namespace Dimsav\Backup\Utilities;

class DefaultsParser
{

    private $config;
    private $defaults;

    public function __construct($fullConfig)
    {
        $this->config = $fullConfig;
        if (isset($this->config['project_defaults']))
        {
            $this->defaults = $this->config['project_defaults'];
        }
    }

    public function parse()
    {
        $this->parseStorages();
        $this->parseMysql();
        $this->unsetDefaults();
        return $this->config;
    }

    private function parseStorages()
    {
        if ( ! isset($this->defaults['storages']))
        {
            return;
        }

        foreach ($this->config['projects'] as $projectName => $projectConfig)
        {
            if ( ! isset($projectConfig['storages']))
            {
                $this->config['projects'][$projectName]['storages'] = $this->defaults['storages'];
            }
        }
    }

    private function parseMysql()
    {
        foreach ($this->config['projects'] as $projectName => $projectConfig)
        {
            $this->parseProjectConfig($projectName);
        }
    }

    private function parseProjectConfig($projectName)
    {
        if ( ! isset($this->config['projects'][$projectName]['mysql']))
        {
            return;
        }
        $projectConfig = $this->config['projects'][$projectName]['mysql'];
        foreach ($projectConfig as $databaseName => $projectDatabaseConfig)
        {
            $this->parseProperty('username', $projectDatabaseConfig);
            $this->parseProperty('password', $projectDatabaseConfig);
            $this->parseProperty('host', $projectDatabaseConfig);
            $this->parseProperty('port', $projectDatabaseConfig);
            $this->config['projects'][$projectName]['mysql'][$databaseName] = $projectDatabaseConfig;
        }
    }

    private function parseProperty($property, &$projectDatabaseConfig)
    {
        $mysqlDefaults = $this->getMysqlDefaults();

        if ( ! isset($projectDatabaseConfig[$property]) && isset($mysqlDefaults[$property]))
        {
            $projectDatabaseConfig[$property] = $mysqlDefaults[$property];
        }
    }

    private function getMysqlDefaults()
    {
        return isset($this->defaults['mysql']) ? $this->defaults['mysql'] : array();
    }

    private function unsetDefaults()
    {
        unset($this->config['project_defaults']);
    }
}
