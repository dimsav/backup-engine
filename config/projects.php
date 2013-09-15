<?php

return array(

    /*
     * The "default" key is used to define the
     * default configuration values of each project.
     *
     * For example, if you define a password here,
     * all the backups will be compressed using this password.
     */

    /*
        "default" => array(

            "database" => array(
                "host" => "localhost",
                "port" => "3306",
                "username" => "db-user",
                "password" => "db-pass",
            ),
            "password" => "secret"
        ),
    */


    /*
     * Define in this array the projects you wish to backup.
     *
     * The key of the array marks the name of the project
     * and it is used for folder and file names. So better
     * use alphanumeric characters with slash/underscores.
     */

    "projects" => array(

        /*
         * Here we define a project to be backed-up.
         * For this project, we want to backup only
         * the database. We use the default host and
         * port, and we override the username and password.
         *
         * For this project we are overriding the default
         * password with another one.
         */

        /*
            "project-1" => array(

                "database" => array(
                    "name"    =>"db-name",
                    "username"=>"db-user",
                    "password"=>"db-pass",
                ),

                "password" => "another-secret",
            ),
        */


        /*
         * For this project we backup both some files
         * and the database.
         *
         * We use the default database settings, so we
         * define only the database name.
         *
         * Under "paths" we put a list of absolute paths
         * of directories or files.
         *
         * Under "excludes" we put a list of absolute paths
         * of directories or files that should not be
         * included in the compressed backup files. The
         * contents of these directories will be skipped
         * recursively.
         */

        /*
            "project-2" => array(

                "database" => array(
                    "name"=>"db-name",
                ),

                "paths" => array(
                    "/absolute/project/folder/path",
                    "/absolute/project/file/text.txt",
                ),

                "excludes" => array(
                    "/absolute/project/folder/path/cache",
                    "/absolute/project/folder/path/logs",
                    "/absolute/project/folder/path/bigfile.tar",
                ),
            ),
        */


        /*
         * Here we disable for project-3 the default password,
         * as we don't want any password for this project.
         */

        /*
            "project-3" => array(
                "paths" => array(
                    "/project/folder",
                ),
                "password" => null,
            ),
        */

    ),

);
