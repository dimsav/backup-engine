<?php

require_once(__DIR__.'/vendor/autoload.php');

use Dimsav\Backup\Application;
use Dimsav\Backup\Config;

if (Config::get('app.debug', true))
{
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

$app = new Application();

$app->run();