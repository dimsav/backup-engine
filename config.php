<?php

// Set your time zone
date_default_timezone_set('Europe/Berlin');

// Set your backups folder
define('BACKUPS', PATH . DS . 'backups');

// Set the projects you want to backup
$projects = array
(
    "my-project-name" => array(
        "db" => array(
            "host"=>"localhost",
            "port"=>"3306",
            "database"=>"database-name",
            "username"=>"database-user",
            "password"=>"database-pass",
        ),
        "files" => array(
            "excludes" => array(),
            "paths" => array("/home/www/project-path")
        ),
    ),

);

// Upload the backup files to this dropbox account
$dropbox_settings = array(
    "email" => "dropbox_email",
    "password" => "dropbox_password",
    "path" => "Backups",    // dropbox folder to save backups
    //"delete_files_after_backup" => true,
);