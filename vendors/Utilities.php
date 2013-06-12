<?php

class Utilities
{
    /*
     * Use this simple function to make sure that the path you are going to use exists.
     */
    public static function use_path($path)
    {
        // If path provided doesn't exist
        if (!is_dir($path))
        {
            // Create it recursively
            mkdir($path, 0777, true);
        }
        return ($path);
    }

    public static function extension($filename = '')
    {
        $filename_parts = explode(".", $filename);
        return end($filename_parts);
    }
}