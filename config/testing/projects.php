<?php

return array(

    "default" => array(
        "base-path" => realpath(__DIR__."/../../"),
        "database" => array(
            "host" => "localhost",
            "port" => "3306",
            "username" => "root",
            "password" => "password",
        ),
        "password" => "testing-default-secret",
        "storages" => "dropbox2"
    ),

    "projects" => array(

        "test-1" => array(

            "paths" => array(
                realpath(__DIR__."/../../src"),
            ),
            "excludes" => array(
                realpath(__DIR__."/../../src/Dimsav/Backup/Config.php"),
            ),
            "password" => null,
            "storages" => 'dropbox1'
        ),
        "test-2" => array(
            "base-path" => realpath(__DIR__."/../../src"),
            "paths" => array('Dimsav'),
            "storages" => array('dropbox1', 'dropbox2')
        ),
        "test-3" => array(
            "database" => array(
                "name"    =>"test_3",
            ),
        )
    ),

    "storages" => array(

        "dropbox1" => array(
            "driver" => "dropbox",
            "username" => "email",
            "password" => "password",
            "destination" => "Backups"
        ),

        "dropbox2" => array(
            "driver" => "dropbox",
            "username" => "email",
            "password" => "password",
            "destination" => "Backups"
        ),

    )

);
