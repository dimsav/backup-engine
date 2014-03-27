<?php namespace Dimsav\Backup\Project\Element;

class AbstractElement implements Element {

    private $extractionDir;

    /**
     * Sets the directory where the extracted file/dir will be saved in.
     *
     * @param $dir
     */
    public function setExtractionDir($dir)
    {
        $this->validate($dir);
        $this->extractionDir = realpath($dir);
    }

    /**
     * @return mixed
     */
    public function getExtractionDir()
    {
        return $this->extractionDir;
    }

    /**
     * Saves the files into the extraction directory
     */
    public function extract()
    {
        // TODO: Implement extract() method.
    }

    /**
     * Returns the absolute path of the dir/file created upon extract()
     */
    public function getExtracted()
    {
        // TODO: Implement getExtracted() method.
    }

    /**
     * @param $dir
     * @throws \InvalidArgumentException
     */
    private function validate($dir)
    {
        if (!is_dir($dir)) {
            throw new \InvalidArgumentException("The directory '$dir' does not exist.");
        }

        if (substr($dir, 0, 1) !== '/') {
            throw new \InvalidArgumentException("The directory '$dir' is not an absolute path.");
        }
    }
}