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
                if ( ! isset($projectConfig['mysql']['username']) && isset($mysqlDefaults['username']))
                {
                    $projectConfig['mysql']['username'] = $mysqlDefaults['username'];
                }
                if ( ! isset($projectConfig['mysql']['password']) && isset($mysqlDefaults['password']))
                {
                    $projectConfig['mysql']['password'] = $mysqlDefaults['password'];
                }
                if ( ! isset($projectConfig['mysql']['host']) && isset($mysqlDefaults['host']))
                {
                    $projectConfig['mysql']['host'] = $mysqlDefaults['host'];
                }
                if ( ! isset($projectConfig['mysql']['port']) && isset($mysqlDefaults['port']))
                {
                    $projectConfig['mysql']['port'] = $mysqlDefaults['port'];
                }
                $this->config['projects'][$projectName] = $projectConfig;
            }
        }

    }

    private function unsetDefaults()
    {
        unset($this->config['project_defaults']);
    }
}
