<?php
/**
 * Created by: Dimitrios Savvopoulos
 * Date: 4/3/12
 *
 * Compress a file or folder using the unix zip function. Can compress using a password or can exclude some files or folders.
 * Note: Won't work in windows.
 *
 */
class UnixZipper
{
    // Basic Settings
    var $source; // file or folder
    var $source_path; // the path of the folder containing the $source file or folder
    var $source_file; // the name of $source file of folder

    var $destination_file = 'file.zip';
    var $target_dir = '';
    var $destination_timestamp = 'Y-m-d_H.i_';

    // Optional Settings
    var $password = '';
    var $excludes = array();

    var $success_message = '';
    var $error_message = '';

    // The command to be run. Only accessible by the class.
    private  $command = '';
    private $errors = array();

    function __construct($source, $target_dir)
    {
        // Check if inputs are ok. If not, set $error_message the appropriate message.
        if (is_dir($source) || is_file($source))
        {
            $this->source = $source;
        }
        else
        {
            $this->error_message = '$source is not a directory of file.';
        }

        if (is_dir($target_dir))
        {
            $this->target_dir = $target_dir;
        }
        else
        {
            $this->error_message = '$target_dir is not a directory.';
        }
    }

    public function compress()
    {
        // If something went wrong with the instantiation of the object, exit.
        if (strlen($this->error_message) > 0)
        {
            return false;
        }

        // Formats $destination_file
        $this->format_target_file();

        $this->format_source();


        $pass = $this->get_password_syntax();

        //$excludes = $this->_get_excluded_part();
        $excludes = '';

        $command = '';

        // First we must cd to the folder that contains our soon to be backed up data
        if (strlen($this->source_path) > 0)
        {
            $command = 'cd ' . $this->source_path . '; ';
        }

        // The zip syntax: -r means recursively
        // zip -r /full/path/of/zipped/file name_of_the_folder_containing_the_data
        $command.=  'zip '. $pass .' ' . $excludes . ' -r '. $this->destination_file  .' ' . $this->source_file;

        $result = exec($command);


        //$result_2 = system('7za a -t7z -mx9 -p'.$pass.' '.$destination.'.7z '.$destination);

        if ($result === false)
        {
            return (false);
        }
        else
        {
            return ($this->destination_file);
        }
    }


    private function format_target_file()
    {

        $this->destination_file = basename($this->source);

        //Add timestamp and extension of zip file to filename (if not set).

        // If the end of $this->destination_file is not .zip
        if (end(explode(".", $this->destination_file)) != 'zip')
        {
            $this->destination_file = date($this->destination_timestamp). $this->destination_file . '.zip';

        }
        else
        {
            $this->destination_file = date($this->destination_timestamp) . substr($this->destination_file, 0, -4) . '.zip';
        }


        if($this->target_dir != '' && substr($this->target_dir, -1, 1) != DIRECTORY_SEPARATOR)
        {
            $this->target_dir .= DIRECTORY_SEPARATOR;
        }

        // Add path to destination file
        $this->destination_file = $this->target_dir . $this->destination_file;

        // TODO: if destination filename exists...
        // count all the files starting with the same name
        // add _2 where 2 is the number found.
    }

    private function format_source()
    {
        if (is_dir($this->source))
        {
            $this->source_path = dirname($this->source);
            $this->source_file = basename($this->source);
        }
        else
        {
            // TODO: if $source is file....
        }
    }

    private function get_password_syntax()
    {
        if ($this->password != '')
        {
            return (' -P ' . $this->password . ' ');
        }
        else
        {
            return ('');
        }
    }

    private function _get_excluded_part()
    {
        $excludes_string = ''; // -x folder/\* -x file.zip

        if (count($this->excludes) == 0)
        {
            return ('');
        }

        foreach ($this->excludes as $exclude)
        {
            // If it's a folder, make sure it ends with foldername/\*
            if ( is_dir($exclude))
            {

                if ( substr($exclude, -1, 1) != '/' )
                {
                    $exclude .= '/';
                }
                $exclude .= '\*';

                $excludes_string .= ' -x '. $exclude .' ';
            }
            else
            {
                $excludes_string .= ' -x '. $exclude .' ';
            }
        }
        return ($excludes_string);
    }
}
