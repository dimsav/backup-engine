<?php

namespace Dimsav\Backup\Project;

class Location
{

    protected $path;

    function __construct($path)
    {
        $this->path = realpath($path);
    }

    public function get()
    {
        return $this->path;
    }
}
