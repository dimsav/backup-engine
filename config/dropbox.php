<?php

/*
 * Configuration for uploading the backup files to a dropbox account.
 */

return array(

    /*
     * Turn dropbox upload on/off.
     */
    "activate" => false,

    /*
     * The credentials of the dropbox account
     */
    "email" => "dropbox@email.com",
    "password" => "dropbox_password",

    // Folder name inside dropbox to store the backup files
    "path" => "Backups",

);
