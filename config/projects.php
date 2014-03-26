<?php

return array(

    /*
     * The "project_defaults" array is used to define the default configuration values of the
     * projects. Only the following keys can be set. Setting one of those keys
     * into a project configuration will overwrite the value.
     */

    "project_defaults" => array(
        "mysql" => array(
            "host" => "localhost",
            "port" => "3306",
            "username" => "root",
            "password" => "password",
        ),
        "password" => "testing-default-secret",
        "storages" => "dropbox1"
    ),

    /*
     * Define in this array the projects you wish to backup. The key of the array marks
     * the name of the project and it is used for folder and file names. So prefer
     * using alphanumeric characters with dash/underscore/points.
     */

    "projects" => array(

        "project1" => array(

            /*
             * To backup project directories, you must set "root_dir" as the absolute path
             * of the project's root directory
             */
            "root_dir" => "/var/websites/project1",

            "directories" => array(

                /*
                 * This is the list of directories that will be backed up. All the
                 * directories should be relative to the project_root. To backup
                 * the whole project_root folder, simply use "/". If "/" is
                 * set, all other directories will be ignored.
                 */
                "src/public/css",
                "src/public/js",

                /*
                 * If you want to backup a directory but exclude some files or directories
                 * inside of it, use an array with the following syntax. The excludes
                 * key can contain a string for excluding one path, or an array
                 * for multiple excludes.
                 *
                 * The example below will backup:
                 *      /var/websites/project1/src/plugins
                 * and exclude
                 *      /var/websites/project1/src/plugins/temp
                 */
                array("/src/plugins", 'excludes' => "temp"),

                array("/src/public/img", 'excludes' => array(
                        "thumbnails", "optimized/thumbnails", "images.log")
                ),

            ),

            /*
             * It is also possible to exclude files/directories related to the project
             * root directory. To do that, use the excludes key at the same level as
             * the directories. Make sure the root_dir is already set!
             */
            "excludes" => array("vendor", "composer.lock", "logs"),

            /*
             * This password field will be used for compressing the backups to zip files.
             * To overwrite a default password (if set), set this to null.
             */
            "password" => "my_secret",

            "mysql" => array(
                "name" => "my_database",
                "host" => "localhost",
                "port" => "3306",
                "username" => "root",
                "password" => "password",
            ),

            /*
             * Storages is an array with the names of the storages we want to use for this
             * project. After the backup files are created, the files will be sent to
             * the storages in the list.
             */
            "storages" => array('my_dropbox', 'dropbox_customer')
        ),

    ),


    /*
     * Here we define a list of the available storages. The key of each array defines the
     * name of the storage. Set it to something you will easily recognize.
     */

    "storages" => array(

        /*
         * At the moment there are two types (drives) of storage. Dropbox and local. To use
         * dropbox, set the driver to "dropbox" and fill the rest of the fields as shown
         * below. "destination" is the path inside the dropbox account that will
         * contain the backup files.
         */

        "my_dropbox" => array(
            "driver" => "dropbox",
            "username" => "email",
            "password" => "password",
            "destination" => "Backups"
        ),

        "dropbox_customer" => array(
            "driver" => "dropbox",
            "username" => "email",
            "password" => "password",
            "destination" => "Backups"
        ),

        /*
         * The local storage will save a copy of the backed up file under the defined
         * directory.
         */

        "my_system" => array(
            "driver" => "local",
            "destination" => "/backups",
        ),

    )

);
