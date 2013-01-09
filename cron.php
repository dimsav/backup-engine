<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('DS', DIRECTORY_SEPARATOR);
define('PATH', dirname(__FILE__));
define('VENDORS', PATH . DS . 'vendors');

require_once(PATH . DS . 'config.php');
require_once(VENDORS . DS . 'Utilities.php');
require_once(VENDORS . DS . 'DropboxUploader' . DS . 'DropboxUploader.php');
require_once(VENDORS . DS . 'UnixZipper.php');
require_once(VENDORS . DS . 'klogger' . DS . 'klogger.class.php');
require_once(VENDORS . DS . 'MysqlBackup' . DS . 'MysqlBackup.php');

Utilities::use_path(BACKUPS);
$error = '';

$log = KLogger::instance(PATH . DS . 'logs', false);
$log->logInfo('Initiating backup...');

if (isset($projects))
{
    foreach ( $projects as $project_name => $project )
    {
        $project_backup_directory = BACKUPS . DS . $project_name;

        // Mysql backup
        if ( isset($project['database']) )
        {
            $database = $project['database'];

            if ( isset($database))
            {
                $backup_obj = new MySQL_Backup();
                $backup_obj->server   = $database['host'];
                $backup_obj->database = $database['database'];
                $backup_obj->port     = $database['port'];
                $backup_obj->username = $database['username'];
                $backup_obj->password = $database['password'];
                $backup_obj->backup_dir = Utilities::use_path(  $project_backup_directory . DS . 'database') . DS  ;
                $backup_obj->fname_format = 'Y-m-d_H.i_';

                $output = 'Backup of database ' . $database['database'] . ': ';
                $backup_result = $backup_obj->Execute(MSB_SAVE);
                if ($backup_result === false)
                {
                    $output .= $backup_obj->error;
                    $log->logError($output);
                    $error .= "\n$output";
                }
                else
                {
                    $upload_to_dropbox[$project_name][] = $backup_result;
                    $output .= 'created successfully.';
                    $log->logInfo($output);
                }
            }
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