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
        $mysqlDefaults = isset($this->defaults['mysql']) ? $this->defaults['mysql'] : null;

        if ( ! $mysqlDefaults)
        {
            return;
        }

        foreach ($this->config['projects'] as $projectName => $projectConfig)
        {
            if ( isset($projectConfig['mysql']))
            {
                foreach ($projectConfig['mysql'] as $databaseName => $databaseConfig)
                {
                    if ( ! isset($databaseConfig['username']) && isset($mysqlDefaults['username']))
                    {
                        $databaseConfig['username'] = $mysqlDefaults['username'];
                    }
                    if ( ! isset($databaseConfig['password']) && isset($mysqlDefaults['password']))
                    {
                        $databaseConfig['password'] = $mysqlDefaults['password'];
                    }
                    if ( ! isset($databaseConfig['host']) && isset($mysqlDefaults['host']))
                    {
                        $databaseConfig['host'] = $mysqlDefaults['host'];
                    }
                    if ( ! isset($databaseConfig['port']) && isset($mysqlDefaults['port']))
                    {
                        $databaseConfig['port'] = $mysqlDefaults['port'];
                    }
                    $this->config['projects'][$projectName]['mysql'][$databaseName] = $databaseConfig;
                }
            }
        }

    }

    private function unsetDefaults()
    {
        unset($this->config['project_defaults']);
    }
}
