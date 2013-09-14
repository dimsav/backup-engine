<?php

/*
 * Define here the projects you wish to backup.
 *
 * The key of the array marks the name of the project
 * and it is used for folder and file names. So better
 * use alphanumeric characters with slash/underscores.
 */

return array(

    /*
     * The "defaults" key is the only exception as
     * it is not a project. It is used to define the
     * default configuration for each project.
     *
     * If you define a password here, all the backups
     * will be compressed using this password.
     */

    "defaults" => array(
        "database" => array(
            "host" => "localhost",
            "port" => "3306",
            "username" => "db-user",
            "password" => "db-pass",
        ),
        "password" => "secret"
    ),

    /*
     * Here we define a project to be backed-up.
     * For this project, we want to backup only
     * the database. We use the default host and
     * port, and we override the username and password.
     *
     * For this project we are overriding the default
     * password with another one.
     */
    "project-1" => array(

        "database" => array(
            "database"=>"db-name",
            "username"=>"db-user",
            "password"=>"db-pass",
        ),

        "password" => "another-secret",
    ),

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
    "project-2" => array(

        "database" => array(
            "database"=>"db-name",
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

    /*
     * Most of the times, projects have just one backup directory.
     * In this case you can use the key "path" and set a string with
     * the absolute path of the directory/file.
     *
     * Same goes for excludes. Use "exclude" and set a string.
     *
     * Note here that there is no strict type needed for paths and
     * excludes. Both keys "paths" and "path" work with string and
     * array types. Just make sure you don't set both keys in the
     * same project. The same applies for "exclude" and "excludes"
     */

    "project-3" => array(

        "database" => array(
            "database"=>"database-name",
        ),

        "path" => "/absolute/project/folder/path",
        "exclude" => "/absolute/project/folder/path/cache",
    ),

);
