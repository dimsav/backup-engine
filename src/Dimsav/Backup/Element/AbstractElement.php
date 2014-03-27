<?php namespace Dimsav\Backup\Element;

abstract class AbstractElement implements Element
{
    protected $extractionDir;
    protected $extractedFiles = array();

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

    /**
     * Returns the absolute path of the files created after extract()
     */
    public function getExtractedFiles()
    {
        return $this->extractedFiles;
    }

}