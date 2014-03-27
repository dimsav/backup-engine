<?php namespace Dimsav\Backup\Element;

abstract class AbstractElement implements Element
{
    private $extractionDir;

    public function setExtractionDir($dir)
    {
        $this->validateExtractionDir($dir);
        $this->extractionDir = realpath($dir);
    }

    /**
     * @param $dir
     * @throws \InvalidArgumentException
     */
    private function validateExtractionDir($dir)
    {
        if (!is_dir($dir)) {
            throw new \InvalidArgumentException("The directory '$dir' does not exist.");
        }

        if (substr($dir, 0, 1) !== '/') {
            throw new \InvalidArgumentException("The directory '$dir' is not an absolute path.");
        }
    }

}