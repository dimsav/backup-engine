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
        $this->parseDatabase();
        $this->unsetDefaults();
        return $this->config;
    }

    private function parseStorages()
    {
        foreach ($this->config['projects'] as $projectName => $projectConfig)
        {
            if ( ! isset($projectConfig['storages']))
            {
                $this->config['projects'][$projectName]['storages'] = $this->defaults['storages'];
            }
       
            $storages = $this->config['projects'][$projectName]['storages'];
            foreach ($storages as &$storage) {
                //replace project name and other details in backup root
                $search = array(
                    '{project}' => $projectName,
                    '{day}' => date('d'),
                    '{month}' => date('m'),
                );

                foreach ($search as $key => $value) {
                    $storage['root'] = str_replace('{project}', $projectName, $storage['root']);
                }
            }
            $this->config['projects'][$projectName]['storages'] = $storages;
         }

    }

    private function parseDatabase()
    {
        foreach ($this->config['projects'] as $projectName => $projectConfig)
        {
            $this->parseProjectConfig($projectName);
        }
    }

    private function parseProjectConfig($projectName)
    {
        if ( ! isset($this->config['projects'][$projectName]['database']))
        {
            return;
        }
        $projectConfig = $this->config['projects'][$projectName]['database'];
        foreach ($projectConfig as $databaseName => $projectDatabaseConfig)
        {
            $this->parseProperty('username', $projectDatabaseConfig);
            $this->parseProperty('password', $projectDatabaseConfig);
            $this->parseProperty('host', $projectDatabaseConfig);
            $this->parseProperty('port', $projectDatabaseConfig);
            $this->config['projects'][$projectName]['database'][$databaseName] = $projectDatabaseConfig;
        }
    }

    private function parseProperty($property, &$projectDatabaseConfig)
    {
        $databaseDefaults = $this->getDatabaseDefaults();

        if ( ! isset($projectDatabaseConfig[$property]) && isset($databaseDefaults[$property]))
        {
            $projectDatabaseConfig[$property] = $databaseDefaults[$property];
        }
    }

    private function getDatabaseDefaults()
    {
        return isset($this->defaults['database']) ? $this->defaults['database'] : array();
    }

    private function unsetDefaults()
    {
        unset($this->config['project_defaults']);
    }
}
