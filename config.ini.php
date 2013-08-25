<?php

// Set your time zone
date_default_timezone_set('Europe/Berlin');

// Set your backups folder
define('BACKUPS', APP_PATH . '/backups');

$config_zip = array(
    // Optional password for the zipped files. To skip the password, leave empty string.
    'password' => ''
);

// Upload the backup files to this dropbox account. To skip dropbox uploading, leave this array empty.
$config_dropbox = array(
    // Dropbox login info
    "email" => "dropbox@email.com",
    "password" => "dropbox_password",
    // Folder name inside dropbox to store the backup files
    "path" => "Backups",
);

// Set the projects you want to backup
$projects = array
(
    "my-project-1" => array(

        "database" => array(
            "host"=>"localhost",
            "port"=>"3306",
            "database"=>"database-name",
            "username"=>"database-user",
            "password"=>"database-pass",
        ),
    ),

    "my-project-2" => array(

        // The project's files and folders
        "paths" => array(
            "/absolute/project/folder/path",
            "/absolute/project/file/text.txt",
        ),

        "exclude_paths" => array(
            // Absolute paths of directories that will be skipped recursively
            "/absolute/project/folder/path/cache",
        )
    ),

    "my-project-3" => array(

        "database" => array(
            "host"=>"localhost",
            "port"=>"3306",
            "database"=>"database-name",
            "username"=>"database-user",
            "password"=>"database-pass",
        ),

        // Can also be a string
        "paths" => "/absolute/project/folder/path",
        "exclude_paths" => "/absolute/project/folder/path/cache",
    ),

);
