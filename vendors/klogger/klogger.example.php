<?php

# Should log to the same directory as this file
require 'KLogger.class.php';

// The folder for saving the log.
// The folder must be absolute and will be created if it doesn't exist.
// If not set, the current file's position will be used.
$folder = dirname(__FILE__) . '/logs';

// Mode determines which logs are going to be printed. See class for details.
// Defaults to false. False prints everything.
$mode = KLogger::DEBUG;


/*
    const EMERG  = 0;  // Emergency: system is unusable
    const ALERT  = 1;  // Alert: action must be taken immediately
    const CRIT   = 2;  // Critical: critical conditions
    const ERR    = 3;  // Error: error conditions
    const WARN   = 4;  // Warning: warning conditions
    const NOTICE = 5;  // Notice: normal but significant condition
    const INFO   = 6;  // Informational: informational messages
    const DEBUG  = 7;  // Debug: debug messages
*/

//$log = KLogger::instance($folder, $mode);
$log = KLogger::instance();

$log->logInfo('Info Test');
$log->logNotice('Notice Test');
$log->logWarn('Warn Test');
$log->logError('Error Test');
$log->logFatal('Fatal Test');
$log->logAlert('Alert Test');
$log->logCrit('Crit test');
$log->logEmerg('Emerg Test');
