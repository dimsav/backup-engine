<?php namespace Dimsav\Backup\Project\Element;

class Directory extends AbstractElement implements Element {

    private $path;

    /**
     * @var Directory
     */
    private $basePath;

    function __construct($path, Directory $basePath = null)
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
     * @return Directory
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

}
