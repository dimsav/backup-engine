<?php namespace Dimsav\Backup;

use Illuminate\Config\FileLoader;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Config\Repository;

class Config {

    private $env;
    private $fileLoader;
    private $repository;

    public function __construct($env = null)
    {
        $this->path = __DIR__.'/../../../config';
        $this->env  = $env ?: 'production';

        $this->fileLoader = new FileLoader(new Filesystem, $this->path);
        $this->repository = new Repository($this->fileLoader, $this->env);

        $this->setupIni();
    }

    public function get($key, $default = null)
    {
        return $this->repository->get($key, $default);
    }

    private function setupIni()
    {
        if ($this->get('app.debug', true))
        {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        }

        date_default_timezone_set($this->get('app.timezone', 'Europe/Berlin'));

        set_time_limit ($this->get('app.time_limit', 0));
    }

}