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
    }

    public function get($key, $default = null)
    {
        return $this->repository->get($key, $default);
    }
}