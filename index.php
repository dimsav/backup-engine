<?php

foreach ( $projects as $projectName => $project )
{
    $projectBackupPath = BACKUPS . "/$projectName";

    \Dimsav\Utilities\FileUtilities::createPathIfNotExisting($projectBackupPath);

    // Mysql backup
    if ( isset($project['database']) && $database = $project['database'] )
    {
        $backup_obj = new MySQL_Backup();
        $backup_obj->server   = $database['host'];
        $backup_obj->database = $database['database'];
        $backup_obj->port     = $database['port'];
        $backup_obj->username = $database['username'];
        $backup_obj->password = $database['password'];
        $backup_obj->backup_dir = "$projectBackupPath/";
        $backup_obj->fname_format = 'Y-m-d_H.i_';

        $output = 'Backup of database ' . $database['database'] . ': ';
        $backup_result = $backup_obj->Execute(MSB_SAVE);
        if ($backup_result === false)
        {
            $output .= $backup_obj->error;
            logError($output);
        }
        else
        {
            $upload_to_dropbox[$projectName][] = $backup_result;
            $output .= "created successfully ($projectName).";
            $log->logInfo($output);
        }
    }

    // Files backup
    $projectPaths         = getProjectPaths($project);
    $projectPathsExcludes = getProjectPathsExcludes($project);
}

// Upload zip files to dropbox
if ( !empty($config_dropbox) )
{
    $dropbox = new DropboxUploader($config_dropbox['email'], $config_dropbox['password']);

    foreach ($upload_to_dropbox as $projectName => $filePaths)
    {
        if (empty($filePaths) || !is_array($filePaths))
        {
            logError('$filePaths format is empty or not an array.');
            continue;
        }

        sendFilesToDropbox($filePaths, $projectName);
    }
    sendFilesToDropbox(getLogFilesPaths(), 'logs');
}

// Helper functions

function getValidPathsOnlyAndLog(&$paths)
{
    foreach ($paths as $key => $path)
    {
        if (!\Dimsav\Utilities\FileUtilities::isValidPath($path))
        {
            logError("The path $path is not a directory or file.");
            unset($paths[$key]);
        }
    }
    return $paths;
}

function sendFilesToDropbox($filePaths, $projectName)
{
    global $dropbox;
    global $log;

    $dropboxDestinationFolder = getDropboxDestinationFolder($projectName);

    foreach ($filePaths as $filePath)
    {
        try {
            $dropbox->upload($filePath, $dropboxDestinationFolder);
            $log->logInfo('File ' . basename($filePath) . " was successfully uploaded to dropbox ($projectName).");
        }
        catch (Exception $e)
        {
            logError('Dropbox: '. $e->getMessage() . " ($projectName) File: $filePath");
        }
    }
}

function getDropboxDestinationFolder($projectName)
{
    global $config_dropbox;
    $dropboxBackupsFolder = isset($config_dropbox['path']) && $config_dropbox['path'] ? $config_dropbox['path'] : 'Backups';
    return  "$dropboxBackupsFolder/$projectName";
}
