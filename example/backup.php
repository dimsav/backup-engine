<?php

require_once(__DIR__.'/../vendor/autoload.php');

if ( ! isset($config))
{
    $config = require(__DIR__.'/config.php');
}

$app = new Dimsav\Backup\Application($config);

if ($app->hasErrors())
{
    echo $app->getErrors()."\n";
}
else
{
    $app->backup();
}