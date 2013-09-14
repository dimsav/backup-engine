<?php

require_once(__DIR__.'/vendor/autoload.php');

use Dimsav\Backup\Application;

$app = new Application();

$app->run();