<?php

namespace Dimsav\Backup\Project;

class Location
{

    protected $path;

    function __construct($path, Location $basePath = null)
    {
        $basePath = $basePath ? $basePath->get() . '/' : '';

        $this->path = realpath($basePath . $path);
    }

    /**
     * Absolute path
     *
     * @return string
     */
    public function get()
    {
        return $this->path;
    }
}
