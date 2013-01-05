<?php

$current_path = dirname(__FILE__);

require_once  $current_path .DS . 'MysqlBackup.php';

if ( isset($database))
{

        $backup_obj = new MySQL_Backup();

        //----------------------- EDIT - REQUIRED SETUP VARIABLES -----------------------

        $backup_obj->server   = $database['host'];
        $backup_obj->database = $database['database'];
        $backup_obj->port     = $database['port'];

        $backup_obj->username = $database['username'];
        $backup_obj->password = $database['password'];

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
        $backup_obj->backup_dir = Utilities::use_path(  $project_backup_directory . DS . 'database') . DS  ;

        //First part of file name. Can be a website name. Defaults to empty string. If left to empty string, database name is used.
        $backup_obj->fname_prefix = '';

        //Second part of file name, used as format in the date() function. Defaults to '_Y-m-d-H-i-s'
        $backup_obj->fname_format = 'Y-m-d_H.i_';


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

        $output = 'Backup of database ' . $database['database'] . ': ';

        $backup_result = $backup_obj->Execute($task);
        if ($backup_result === false)
        {
            $output .= $backup_obj->error;
            $log->logError($output);
            $error .= "\n$output";

        }
        else
        {
            $upload_to_dropbox[$project_name][] = $backup_result;
            $output .= 'created successfully.';
            $log->logInfo($output);
        }
        // log and/or email $output;

        // TODO: upload the backup file somewhere or fill the files_to_be_uploaded array
}
