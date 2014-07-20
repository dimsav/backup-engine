<?php

return array(

    "project_defaults" => array(
        "mysql" => array(
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

            "mysql" => array(
                "my_database" => array(
                    "host" => "localhost",
                    "port" => "3306",
                    "username" => "root",
                    "password" => "secret",
                )
            ),

            "storages" => array('my_dropbox', 'my_system')
        ),

    ),

    "storages" => array(

        "my_dropbox" => array(
            "driver" => "dropbox",
            "username" => "dropbox@example.com",
            "destination" => "/Backups",
            "clean" => "-7 days", //All relative formats. Ex "last monday" :: http://php.net/manual/en/datetime.formats.relative.php
        ),

        "my_system" => array(
            "driver" => "local",
            "destination" => "/backups",
            "clean" => "-7 days", //All relative formats. Ex "last monday" :: http://php.net/manual/en/datetime.formats.relative.php
        ),
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
