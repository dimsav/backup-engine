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
     * Set debug to true if you want to enable displaying
     * of errors and notices.
     */
    "debug" => true,

    /*
     * Set here the absolute path of the directory containing
     * the saved backup files. Even if the directory doesn't
     * exist, the code will create it for you.
     */
    "backups_dir" => __DIR__ .'/../backups',
);
