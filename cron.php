<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('DS', DIRECTORY_SEPARATOR);
define('PATH', dirname(__FILE__));
define('VENDORS', PATH . DS . 'vendors');

// Enable errors
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', PATH . '/logs/error_log.txt');

require_once(PATH . DS . 'config.php');
require_once(VENDORS . DS . 'Utilities.php');
require_once(VENDORS . DS . 'DropboxUploader' . DS . 'DropboxUploader.php');
require_once(VENDORS . DS . 'UnixZipper.php');

Utilities::use_path(BACKUPS);

// Creates the $log object of the klogger class.
require_once(VENDORS . DS . 'klogger' . DS . 'run.klogger.php');

$error = '';
$log->logInfo('Initiating backup...');

if (isset($projects))
{
    foreach ($projects as $project_name => $project)
    {
        // If the name of the project is not defined in the array,
        if (is_int($project_name))
        {
            // set the name as 'project_0'
            $project_name = 'project_' . $project_name;
        }

        // The directory for the project's backup
        $project_backup_directory = BACKUPS . DS . $project_name;

        // Mysql backup
        if (array_key_exists('db', $project))
        {
            $database = $project['db'];

            include(VENDORS . DS . 'MysqlBackup' . DS . 'run.backup.mysql.php');
        }

        // Files backup
        if ( isset($project['files']) )
        {
            $zip_directories = $project['files'];

            // Todo: I don't think this is working correctly
            // If we want to exclude some directories
            if ( isset($zip_directories['excludes']) && !empty($zip_directories['excludes']) )
            {
                $excludes = $zip_directories['excludes'];
                unset($zip_directories['excludes']);
            }

            // TODO: save in an array all the files created by the backup, so dropbox uploader can upload them
            include(VENDORS . DS . 'unix.zip' . DS . 'run.unix.zip.php');

            foreach ($zip_directories as $source_directory)
            {
                if (!is_dir($source_directory) && !is_file($source_directory))
                {
                    $log->logError('Path ' . $source_directory . ' is not directory or file.');
                    $error .= "\n".'Path ' . $source_directory . ' is not directory or file.';
                }

                $target_directory = Utilities::use_path($project_backup_directory . DS . 'files');

                $unix_zipper = new UnixZipper($source_directory, $target_directory);

                // TODO: see how excluded folders work.
                //$unix_zipper->excludes = array('covers');
                // If you want to zip the files with password, uncomment this line.
                // $unix_zipper->password = 'my_password';


                if ( $unix_zipper->compress() === false )
                {
                    $log->logError('Path ' . $source_directory . ' could not be compressed. Class returned false.');
                    $error .= "\n".'Path ' . $source_directory . ' could not be compressed. Class returned false. ';
                }
                else
                {
                    $upload_to_dropbox[$project_name][] = $compress_result;
                }
            }
        }
    }

    // If dropbox settings set and list of files to be uploaded exists, upload files to dropbox
    if ( isset($dropbox_settings) && !empty($dropbox_settings) )
    {
        foreach ($upload_to_dropbox as $project_name => $upload_files)
        {
            if (isset($upload_files) && isset($dropbox_settings))
            {
                foreach ($upload_files as $upload_file)
                {
                    // If we are not already connected to dropbox (to avoid our ip to get blocked from Dropbox)
                    if ( !isset($uploader) || $dropbox_settings['email'] != $dropbox_email )
                    {
                        $dropbox_email = $dropbox_settings['email'];
                        $dropbox_password = $dropbox_settings['password'];
                        $uploader = new DropboxUploader($dropbox_email, $dropbox_password);
                    }

                    if( array_key_exists('path',$dropbox_settings) )
                    {
                        $dropbox_destination_folder = $dropbox_settings['path'] . '/' . $project_name;
                    }
                    else
                    {
                        $dropbox_destination_folder = $project_name;
                    }

                    // Upload
                    $uploader->upload($upload_file, $dropbox_destination_folder);

                    $log->logInfo('File ' . basename($upload_file) . ' was successfully uploaded to dropbox');
                }
            }
            else
            {
                $log->logError('Dropbox settings were not found');
                $error .= "\nDropbox settings were not found ";
            }
        }
    }
}
else
{
    $log->logError('$projects variable is not defined. Please make sure the config.php is included.');
    $error .= "\n".'$projects variable is not defined. Please make sure the config.php is included. ';
}

if ( !empty($error) )
{
    // todo: send email
    echo $error;
}
else
{
    echo 'success!';
}