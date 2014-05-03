<?php

use Dimsav\Backup\Application;

require_once(__DIR__.'/vendor/autoload.php');

if ( ! isset($config))
{
    $config = include(__DIR__.'/config/config.php');
}

$app = new Application($config);
$app->backup($tempDir);