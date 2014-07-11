<?php

return array(

    "project_defaults" => array(
        "database" => array(
            "driver" => "mysql",
            "host" => "localhost",
            "port" => "3306",
            "username" => "root",
            "password" => "secret",
        ),
        "storages" => "my_dropbox"
    ),

    "projects" => array(

        "project1" => array(

            "root_dir" => "/var/websites/project1",

            "directories" => array(
                "src/public/css",
                "src/plugins" => array('excludes' => "temp"),
                "src/public/img" => array(
                    'excludes' => array(
                        "thumbnails", "optimized/thumbnails", "images.log"
                )),
                "/" => array("excludes" => array("vendor", "composer.lock", "logs")),
            ),

            "database" => array(
                "my_database" => array(
                    "driver" => "mysql",
                    "host" => "localhost",
                    "port" => "3306",
                    "username" => "root",
                    "password" => "secret",
                )
            ),

            "storages" => array('sftp', 'my_system')
        ),

    ),

    "storages" => array(

        "my_system" => array(
            "driver" => "local",
            "root" => "/backups",
        ),
        "sftp" => array(
            'driver' => 'sftp',
            'host' => 'example.com',
            'port' => 22,
            'username' => 'username',
            'password' => 'password',
            'privateKey' => 'path/to/or/contents/of/privatekey',
            'root' => '/path/to/backup/',
            'timeout' => 10,
        ),
        "ftp" => array(
            'driver' => 'ftp',
            'host' => 'ftp.example.com',
            'username' => 'username',
            'password' => 'password',

            /** optional config settings */
            'port' => 21,
            'root' => '/path/to/backup',
            'passive' => true,
            'ssl' => true,
            'timeout' => 30,
        )
    ),

    "app" => array(

        /*
         * It is not safe to rely on the system's timezone,
         * especially when we are dealing with backup files.
         */
        "timezone" => 'Europe/Berlin',

        /*
         * =============================================
         *
         *  Changing the settings below is not required
         *
         * =============================================
         */

        /*
         * Maximum time execution limit in seconds.
         * Set it to zero to remove the limit.
         */
        "time_limit" => 0,

        /*
         * Set here the absolute path of the temp directory used for creating the backup files.
         * Make sure the app can create and write to this folder.
         *
         * WARNING: This directory is being deleted before and after each backup!
         *          Don't change it if you don't know what you're doing!
         */
        "temp_dir" => __DIR__ .'/../temp',


        /*
         * Debugging and logging
         */

        /*
         * If set to true, all the logs will be echoed.
         */
        "echo_output" => true,

        /*
         * Path of the log file. It contains logs of all levels.
         */
        "log" => __DIR__ .'/../logs/log.txt',

        /*
         * Path of the error log file.
         */
        "error_log" => __DIR__ .'/../logs/errors.txt',

    )
);
