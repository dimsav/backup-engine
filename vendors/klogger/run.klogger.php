<?php

$current_directory = dirname(__FILE__);

require $current_directory . DS . 'klogger.class.php';

/*
 *
 * The folder for saving the log.
 * The folder must be absolute and will be created if it doesn't exist.
 * If not set, the current file's position will be used.
 *
 */

$log_folder = PATH . DS . 'logs';


/*
 *   Mode:
 *
 *   Mode determines which logs are going to be printed. See class for details.
 *   Defaults to false. False prints everything.
 *
 *   Example:
 *
 *   $mode = KLogger::DEBUG;
 *
 *
 *   List of available modes:
 *
 *   const EMERG  = 0;  // Emergency: system is unusable
 *   const ALERT  = 1;  // Alert: action must be taken immediately
 *   const CRIT   = 2;  // Critical: critical conditions
 *   const ERR    = 3;  // Error: error conditions
 *   const WARN   = 4;  // Warning: warning conditions
 *   const NOTICE = 5;  // Notice: normal but significant condition
 *   const INFO   = 6;  // Informational: informational messages
 *   const DEBUG  = 7;  // Debug: debug messages
 */

$log_mode = false;

$log = KLogger::instance($log_folder, $log_mode);
