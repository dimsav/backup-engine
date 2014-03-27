<?php namespace Dimsav\Backup\Element;

interface Element {

    /**
     * Sets the directory where the extracted file/dir will be saved in.
     *
     * @param $dir
     */
    public function setExtractionDir($dir);

    /**
     * Saves the files into the extraction directory
     */
    public function extract();

    /**
     * Returns the absolute path of the files created after extract()
     */
    public function getExtractedFiles();

}