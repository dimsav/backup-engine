<?php

return array(

    "default" => array(

        "database" => array(
            "host" => "localhost",
            "port" => "3306",
            "username" => "db-user",
            "password" => "db-pass",
        ),
        "password" => "testing-default-secret"
    ),

    "projects" => array(

        "testing-project-1" => array(

            "database" => array(
                "name"    =>"testing-db-name",
            ),
            "paths" => array(
                realpath(__DIR__."/../../src"),
            ),
            "excludes" => array(
                realpath(__DIR__."/../../src/Dimsav/Backup/Config.php"),
            ),
            "password" => null,
        ),
        "project-db-only" => array(

            "database" => array(
                "name"    =>"testing-db-name",
            ),
        ),

    ),

);
