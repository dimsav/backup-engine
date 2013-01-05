<?php


/*
        |--------------------------------------------------|
        |	Example MySQL Backup File                      |
        |	                                               |
        |	Written by: Justin Keller <kobenews@cox.net>   |
        |   Released under GNU Public license.             |
        |                                                  |
        |	Only use with MySQL database backup class,     |
        |	version 1.0.1 written by Vagharshak Tozalakyan |
        |	<vagh@armdex.com>, updated by Dimitrios        |
        |   Savvopoulos <ds@dimsav.com                     |
        |--------------------------------------------------|
    */

require_once  PATH .DS . 'MysqlBackup'. DS . 'MysqlBackup.php';
$backup_obj = new MySQL_Backup();

//----------------------- EDIT - REQUIRED SETUP VARIABLES -----------------------

$backup_obj->server = MYSQL_SERVER;
$backup_obj->port = MYSQL_PORT;
$backup_obj->username = MYSQL_USERNAME;
$backup_obj->password = MYSQL_PASSWORD;
$backup_obj->database = MYSQL_DATABASE;

//------------------------ END - REQUIRED SETUP VARIABLES -----------------------

//-------------------- OPTIONAL PREFERENCE VARIABLES ---------------------

//Tables you wish to backup. All tables in the database will be backed up if this array is null. Defaults to array().
$backup_obj->tables = array();

//Add DROP TABLE IF EXISTS queries before CREATE TABLE in backup file. Defaults to true.
$backup_obj->drop_tables = true;

//Only structure of the tables will be backed up if true. Defaults to false.
$backup_obj->struct_only = false;

//Include comments in backup file if true. Defaults to true.
$backup_obj->comments = true;

//Directory on the server where the backup file will be placed. Used only if task parameter equals MSB_SAVE. Defaults to empty string.
//Add directory separator in the end if used. Can be relative or absolute.
$backup_obj->backup_dir = BACKUP_MYSQL_DIR . DS  ;

//First part of file name. Can be a website name. Defaults to empty string. If left to empty string, database name is used.
$backup_obj->fname_prefix = '';

//Second part of file name, used as format in the date() function. Defaults to '_Y-m-d-H-i-s'
$backup_obj->fname_format = '_Y-m-d-H-i';


//--------------------- END - OPTIONAL PREFERENCE VARIABLES ---------------------

//---------------------- EDIT - REQUIRED EXECUTE VARIABLES ----------------------

/*
                Task:
                    MSB_STRING - Return SQL commands as a single output string. (επιστρέφει το backup για μεταβλητή)
                    MSB_SAVE - Create the backup file on the server.
                    MSB_DOWNLOAD - Download backup file to the user's computer.

            */

//$task = MSB_STRING;
$task = MSB_SAVE;
//$task = MSB_DOWNLOAD;

//Optional name of backup file if using 'MSB_SAVE' or 'MSB_DOWNLOAD'. If nothing is passed, the default file name format will be used.
$filename = '';

//Use GZip compression if using 'MSB_SAVE' or 'MSB_DOWNLOAD'?
$backup_obj->compress = true;

//--------------------- END - REQUIRED EXECUTE VARIABLES ----------------------

//-------------------- NO NEED TO ANYTHING BELOW THIS LINE --------------------
if (!$backup_obj->Execute($task)) {
    $output = $backup_obj->error;
}
else
{
    $output = 'Backup of ' . $backup_obj->fname_prefix .' Completed Successfully At: <b>' . date('g:i:s A') . '</b><i> ( Local Server Time )</i>';
}
// log and/or email $output;
echo $output;
