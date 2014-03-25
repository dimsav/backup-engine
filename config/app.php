<?php

return array(

    /*
     * It is not safe to rely on the system's timezone,
     * especially when we are dealing with backup files.
     */
    "timezone" => 'Europe/Berlin',

    /*
     * Maximum time execution limit in seconds.
     * Set it to zero to remove the limit.
     */
    "time_limit" => 0,

    /*
     * Set here the absolute path of the temp directory used for creating the backup files.
     * Make sure the user has write access to it.
     */
    "temp_dir" => __DIR__ .'/../backups',

    /*
     * Timestamp prefix added to every generated backup file.
     * This string is added to the date() function.
     *
     * Do not leave this empty, as the older backup files
     * will be over-written by the new ones.
     */
    "timestamp_prefix" => "Y.m.d.H.i.",


    /*
     * Debugging and logging
     */

    /*
     * If set to true, all the logs will be echoed. Also the
     * php errors will be displayed.
     */
    "debug" => true,

    /*
     * Path of the log file. It contains logs of all levels.
     * To disable it, set it to false.
     */
    "log" => __DIR__ .'/../logs/log.txt',

    /*
     * Path of the error log file.
     * To disable it, set it to false.
     */
    "error_log" => __DIR__ .'/../logs/errors.txt',

);
