<?php namespace Dimsav\Backup\Project\Element;

interface ElementInterface {

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
     * Returns the absolute path of the dir/file created upon extract()
     */
    public function getExtracted();

}