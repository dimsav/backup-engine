<?php

namespace Dimsav\Backup\Project;

class Location
{

    protected $path;

    function __construct($path, $basePath = null)
    {
        $basePath = $basePath ? realpath($basePath) . '/' : '';

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
