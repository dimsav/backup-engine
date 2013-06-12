<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

define('DS', DIRECTORY_SEPARATOR);
define('PATH', dirname(__FILE__));

define('VENDORS', PATH . DS . 'vendors');

require_once(PATH    . DS . 'config.php');
require_once(VENDORS . DS . 'Utilities.php');
require_once(VENDORS . DS . 'DropboxUploader' . DS . 'DropboxUploader.php');
require_once(VENDORS . DS . 'UnixZipper.php');
require_once(VENDORS . DS . 'klogger' . DS . 'klogger.class.php');
require_once(VENDORS . DS . 'MysqlBackup' . DS . 'MysqlBackup.php');

$log = KLogger::instance(PATH . DS . 'logs', false);
$log->echo = true;