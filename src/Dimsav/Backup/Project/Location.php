<?php namespace Dimsav\Backup\Project;

class Location
{

    private $path;

    /**
     * @var Location
     */
    private $basePath;

    function __construct($path, Location $basePath = null)
    {
        $this->basePath = $basePath;
        $this->path = $path;
    }

    /**
     * Absolute path
     *
     * @return string
     */
    public function get()
    {
        $basePath = $this->basePath ? $this->basePath->get() . '/' : '';
        return realpath($basePath . $this->path);
    }

    /**
     * @return \Dimsav\Backup\Project\Location
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

}
