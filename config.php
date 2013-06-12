<?php

// Set your time zone
date_default_timezone_set('Europe/Berlin');

// Set your backups folder
define('BACKUPS', PATH . DS . 'backups');

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
    "my-first-project-name" => array(

        // To skip database backup, you can skip this parameter
        "database" => array(
            "host"=>"localhost",
            "port"=>"3306",
            "database"=>"database-name",
            "username"=>"database-user",
            "password"=>"database-pass",
        ),

        // The project's files and folders
        "folders" => array(
            array(
                // Location can be the absolute path of a directory or a file.
                'location' => "/absolute/project-path",
                // Relative folder or files to the above location
                // In this example the folders /absolute/project-path/logs and /absolute/project-path/downloads
                // and the file /absolute/project-path/root/index.php won't be included in the backup.
                // If "location" is a file, you can just skip the excludes parameter.
                'excludes' => array('logs', 'downloads', 'root/index.php')
            ),
            // Backup for each project as many separated folders as you wish
            array(
                'location' => "/absolute/project-path-2",
                'excludes' => array('pdf')
            )
        ),
    ),

    "my-second-project-name" => array(
        // ...
    ),

);
