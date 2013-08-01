<?php

class Utilities
{
    /*
     * Creates directory recursively
     */
    public static function createPathIfNotExisting($path)
    {
        if (!is_dir($path))
        {
            mkdir($path, 0777, true);
        }
    }

    public static function getFileNameExtension($filename = '')
    {
        $filename_parts = explode(".", $filename);
        return end($filename_parts);
    }

    public static function isValidPath($path)
    {
        return is_dir($path) || is_file($path);
    }

}