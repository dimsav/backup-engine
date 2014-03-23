<?php namespace Dimsav\Backup\Project\Element;

interface ElementInterface {

    /**
     * Sets the directory where the extracted file/dir will be saved in.
     *
     * @param $absoluteDirPath
     */
    public function setExtractionDir($absoluteDirPath);

    /**
     * Saves the files into the extraction directory
     */
    public function extract();

    /**
     * Returns the absolute path of the dir/file created upon extract()
     */
    public function getExtracted();

}