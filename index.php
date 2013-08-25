<?php

require_once(__DIR__.'/start.php');

Utilities::createPathIfNotExisting(BACKUPS);

$log->logInfo('Initiating backup...');

if (empty($projects))
{
    logError('$projects variable is not defined. Please make sure the config.php is included.');
    die;
}

foreach ( $projects as $projectName => $project )
{
    $projectBackupPath = BACKUPS . "/$projectName";

    Utilities::createPathIfNotExisting($projectBackupPath);

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

    foreach ($projectPaths as $projectPath)
    {
        $unix_zipper = new UnixZipper($log, new Utilities());
        $unix_zipper->setPathToBeZipped($projectPath);
        $unix_zipper->setZipFileDirectoryPath($projectBackupPath);
        $unix_zipper->setExcludes($projectPathsExcludes);

        if ( getZipPassword() )
        {
            $unix_zipper->setPassword(getZipPassword());
        }

        if ( $unix_zipper->compress() )
        {
            $upload_to_dropbox[$projectName][] = $unix_zipper->getZipFilePath();

            $log->logInfo("Created file ". $unix_zipper->getZipFileName() . " ($projectName).");
        }
        else
        {
            logError("$projectPath could not be compressed. ($projectName).");
        }
    }
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

function getProjectPaths($project){
    $paths = !isset($project['paths']) ? array() : stringOrArrayToArray($project['paths']);
    return getValidPathsOnlyAndLog($paths);
}

function getProjectPathsExcludes($project)
{
    $paths = !isset($project['exclude_paths']) ? array() : stringOrArrayToArray($project['exclude_paths']);
    return getValidPathsOnlyAndLog($paths);
}

function stringOrArrayToArray($input)
{
    return is_array($input) ? $input : array($input);
}

function getValidPathsOnlyAndLog(&$paths)
{
    foreach ($paths as $key => $path)
    {
        if (!Utilities::isValidPath($path))
        {
            logError("The path $path is not a directory or file.");
            unset($paths[$key]);
        }
    }
    return $paths;
}

function logError($message)
{
    global $log;
    $log->logError($message);
}

function getZipPassword()
{
    global $config_zip;
    return isset($config_zip['password']) ? $config_zip['password'] : false;
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

function getLogFilesPaths()
{
    $logFiles = array();

    foreach(scandir(LOGS_PATH) as $file)
    {
        if (Utilities::getFileNameExtension($file) == 'txt')
            $logFiles[] = LOGS_PATH."/$file";
    }
    return $logFiles;
}