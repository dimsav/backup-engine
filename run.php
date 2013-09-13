<?php

require_once(__DIR__.'/vendor/autoload.php');

use Dimsav\PhpMysqlBackup\Application;

$app = new Application();

$app->run();