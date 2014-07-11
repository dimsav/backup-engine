[![Latest Stable Version](http://img.shields.io/packagist/v/dimsav/backup-engine.svg)](https://packagist.org/packages/dimsav/backup-engine)
[![Build Status](https://travis-ci.org/dimsav/backup-engine.svg?branch=master)](https://travis-ci.org/dimsav/backup-engine)
[![Code Coverage](https://scrutinizer-ci.com/g/dimsav/backup-engine/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/dimsav/backup-engine/)
[![License](https://poser.pugx.org/dimsav/backup-engine/license.svg)](https://packagist.org/packages/dimsav/backup-engine)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/4886bcc4-19d8-4939-8f14-51688321df89/mini.png)](https://insight.sensiolabs.com/projects/4886bcc4-19d8-4939-8f14-51688321df89)

# (Beta1 Release) Website backup library (PHP, MySQL, Dropbox)

This is a library, written in php, used to backup your project's files and databases.

* [Installation](#installation)

**Composer**

**1)** Add the package to "require" in composer.json

```JSON
"require": {
    "dimsav/backup-engine": "dev-master"
}
```

**2)** Update your composer packages.

`composer update`

## Features

* Multiple projects can be backed up at once
* Custom selection of backup directories per project
* Custom selection of excluded paths
* Password protection of backup files (.zip)
* Detailed logs are saved to the server and are uploaded to dropbox.

## Requirements

- PHP 5.4
- MySQL support requires `mysqldump` and `mysql` command-line binaries
- PostgreSQL support requires `pg_dump` and `psql` command-line binaries
- zip support requires `zip` command-line binaries
- The function exec() should be available.


## Instructions

1. Copy config/config.ini.php to config/config.php.
2. Edit config.php to define your projects to be backed up.
3. Run backup.php using the command line or a web server.
4. See the magic happen!

Defining your projects is a piece of cake:

```php
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
        "project-1" => array(

            "database" => array(
                "db-name" => array(
                    "username"=>"db-user",
                    "password"=>"db-pass"
                )
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
                "db-name" => array(
                    "database"    =>"db-name",
                )
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
         * Here we disable for project-3 the default password,
         * as we don't want any password for this project.
         */
        "project-3" => array(
            "paths" => array(
                "/project/folder",
            ),
            "password" => null,
        )

    ),
    "storages" => array(
        
        "my_system" => array(
            "driver" => "local",
            "root" => "/path/to/backup",
        ),
        "sftp" => array(
            'driver' => 'sftp',
            'host' => 'example.com',
            'port' => 22,
            'username' => 'username',
            'password' => 'password',
            'privateKey' => 'path/to/or/contents/of/privatekey',
            'root' => '/path/to/backup',
            'timeout' => 10,
        ),
        "ftp" => array(
            'driver' => 'ftp',
            'host' => 'ftp.example.com',
            'username' => 'username',
            'password' => 'password',

            /** optional config settings */
            'port' => 21,
            'root' => '/path/to/root',
            'passive' => true,
            'ssl' => true,
            'timeout' => 30,
        )
    ),
```

### License

This package is licensed under the MIT license. Read LICENCE file for more information
