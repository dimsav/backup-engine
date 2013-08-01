<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

set_time_limit ( 0 ); // Disable time limit

define('DS', DIRECTORY_SEPARATOR);
define('APP_PATH', dirname(__FILE__));

define('VENDORS', APP_PATH . DS . 'vendors');
define('LOGS_PATH', APP_PATH . DS . 'logs');

require_once(APP_PATH . "/config.php");
require_once(VENDORS  . "/Utilities.php");
require_once(VENDORS  . "/DropboxUploader/DropboxUploader.php");
require_once(VENDORS  . "/UnixZipper.php");
require_once(VENDORS  . "/klogger/klogger.class.php");
require_once(VENDORS  . "/MysqlBackup/MysqlBackup.php");

$log = KLogger::instance(LOGS_PATH, false);
$log->echo = true;