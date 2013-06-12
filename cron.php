<?php

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'start.php');

Utilities::use_path(BACKUPS);

$log->logInfo('Initiating backup...');

if (empty($projects))
{
    $log->logError('$projects variable is not defined. Please make sure the config.php is included.');
    die;
}

foreach ( $projects as $project_name => $project )
{
    $project_backup_directory = BACKUPS . DS . $project_name;


    // Mysql backup
    if ( isset($project['database']) && $database = $project['database'] )
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
        }
        else
        {
            $upload_to_dropbox[$project_name][] = $backup_result;
            $output .= 'created successfully.';
            $log->logInfo($output);
        }
    }


    // Files backup
    if ( !empty($project['folders']) )
    {
        foreach ($project['folders'] as $config_folder)
        {
            $folder   = $config_folder['location'];
            $excludes = empty($config_folder['excludes']) ? array() : $config_folder['excludes'];

            if (!is_dir($folder) && !is_file($folder))
            {
                $log->logError('Path ' . $folder . ' is not directory or file.');
                continue;
            }

            $target_directory = Utilities::use_path($project_backup_directory . DS . 'files');

            $unix_zipper = new UnixZipper($folder, $target_directory);

            $unix_zipper->password = empty($config_zip['password']) ? '' : $config_zip['password'];
            $unix_zipper->excludes = $excludes;

            if ( ($compress_result = $unix_zipper->compress()) === false )
            {
                $log->logError('Path ' . $folder . ' could not be compressed. Class returned false.');
            }
            else
            {
                $upload_to_dropbox[$project_name][] = $compress_result;
            }
        }
    }
}


// Upload zip files to dropbox
if ( !empty($config_dropbox) )
{
    $dropbox_email = $config_dropbox['email'];
    $dropbox_password = $config_dropbox['password'];
    $uploader = new DropboxUploader($dropbox_email, $dropbox_password);

    foreach ($upload_to_dropbox as $project_name => $upload_files)
    {
        if (empty($upload_files) || !is_array($upload_files))
        {
            $log->logError('$upload_files format is empty or not an array.');
            continue;
        }

        foreach ($upload_files as $upload_file)
        {
            if( array_key_exists('path',$config_dropbox) )
            {
                $dropbox_destination_folder = $config_dropbox['path'] . '/' . $project_name;
            }
            else
            {
                $dropbox_destination_folder = $project_name;
            }

            // Send file to dropbox
            try {
                $uploader->upload($upload_file, $dropbox_destination_folder);
                $log->logInfo('File ' . basename($upload_file) . ' was successfully uploaded to dropbox.');
            }
            catch (Exception $e)
            {
                $log->logError('Dropbox: '. $e->getMessage());
            }
        }
    }
}
