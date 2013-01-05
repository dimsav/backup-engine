<?php

/*
    MySQL database backup class, version 1.0.1
    Written by Vagharshak Tozalakyan <vagh@armdex.com>
    Released under GNU Public license
    http://www.phpclasses.org/browse/file/12128.html
    Updated by Dimitrios Savvopoulos
*/

define('MSB_VERSION', '1.0.1');
define('MSB_NL', "\r\n");
define('MSB_STRING', 0);
define('MSB_DOWNLOAD', 1);
define('MSB_SAVE', 2);

class MySQL_Backup
{

    var $server = 'localhost';
    var $port = 3306;
    var $username = 'root';
    var $password = '';
    var $database = '';

    var $link_id = -1;
    var $connected = false;
    var $tables = array();
    var $drop_tables = true;
    var $struct_only = false;
    var $comments = true;

    var $backup_dir = '';
    var $fname_prefix = '';
    var $fname_format = '_Y-m-d-H-i-s';

    var $compress = true;

    var $error = '';


    function Execute($task = MSB_STRING)
    {
        if (!($sql = $this->_Retrieve()))
        {
            return false;
        }

        $fname_final = $this->_SetFileName($task);



        if ($task == MSB_SAVE)
        {
            return $this->_SaveToFile($fname_final, $sql);
        }
        elseif ($task == MSB_DOWNLOAD)
        {
            return $this->_DownloadFile($fname_final, $sql);
        }
        else
        {
            return $sql;
        }
    }

    private function _SetFileName($task)
    {
        $fname = '';

        // If
        if ($task == MSB_SAVE)
        {
            $fname .= $this->backup_dir;
        }

        // If file name not set, use the database name.
        if ($this->fname_prefix == '')
        {
           $this->fname_prefix = $this->database;
        }

        $fname .= date($this->fname_format) . $this->fname_prefix;

        // Set file extention according to the compression setting
        $fname .= ($this->compress ? '.sql.gz' : '.sql');

        return $fname;

    }

    function _Connect()
    {
        $value = false;
        if (!$this->connected) {
            $host = $this->server . ':' . $this->port;
            $this->link_id = mysql_connect($host, $this->username, $this->password);
        }
        if ($this->link_id) {
            if (empty($this->database)) {
                $value = true;
            }
            elseif ($this->link_id !== -1)
            {
                $value = mysql_select_db($this->database, $this->link_id);
            }
            else
            {
                $value = mysql_select_db($this->database);
            }
            mysql_query("SET NAMES 'utf8'", $this->link_id);
        }
        if (!$value) {
            $this->error = mysql_error();
        }
        return $value;
    }


    function _Query($sql)
    {
        if ($this->link_id !== -1) {
            $result = mysql_query($sql, $this->link_id);
        }
        else
        {
            $result = mysql_query($sql);
        }
        if (!$result) {
            $this->error = mysql_error();
        }
        return $result;
    }


    function _GetTables()
    {
        $value = array();
        if (!($result = $this->_Query('SHOW TABLES'))) {
            return false;
        }
        while ($row = mysql_fetch_row($result))
        {
            if (empty($this->tables) || in_array($row[0], $this->tables)) {
                $value[] = $row[0];
            }
        }
        if (!sizeof($value)) {
            $this->error = 'No tables found in database.';
            return false;
        }
        return $value;
    }


    function _DumpTable($table)
    {
        $value = '';
        $this->_Query('LOCK TABLES ' . $table . ' WRITE');
        if ($this->comments) {
            $value .= '#' . MSB_NL;
            $value .= '# Table structure for table `' . $table . '`' . MSB_NL;
            $value .= '#' . MSB_NL . MSB_NL;
        }
        if ($this->drop_tables) {
            $value .= 'DROP TABLE IF EXISTS `' . $table . '`;' . MSB_NL;
        }
        if (!($result = $this->_Query('SHOW CREATE TABLE ' . $table))) {
            return false;
        }
        $row = mysql_fetch_assoc($result);
        $value .= str_replace("\n", MSB_NL, $row['Create Table']) . ';';
        $value .= MSB_NL . MSB_NL;
        if (!$this->struct_only) {
            if ($this->comments) {
                $value .= '#' . MSB_NL;
                $value .= '# Dumping data for table `' . $table . '`' . MSB_NL;
                $value .= '#' . MSB_NL . MSB_NL;
            }
            $value .= $this->_GetInserts($table);
        }
        $value .= MSB_NL . MSB_NL;
        $this->_Query('UNLOCK TABLES');
        return $value;
    }


    function _GetInserts($table)
    {
        $value = '';
        if (!($result = $this->_Query('SELECT * FROM ' . $table))) {
            return false;
        }
        while ($row = mysql_fetch_row($result))
        {
            $values = '';
            foreach ($row as $data)
            {
                $values .= '\'' . addslashes($data) . '\', ';
            }
            $values = substr($values, 0, -2);
            $value .= 'INSERT INTO ' . $table . ' VALUES (' . $values . ');' . MSB_NL;
        }
        return $value;
    }


    function _Retrieve()
    {
        $value = '';
        if (!$this->_Connect()) {
            return false;
        }
        if ($this->comments) {
            $value .= '#' . MSB_NL;
            $value .= '# MySQL database dump' . MSB_NL;
            $value .= '# Created by MySQL_Backup class, ver. ' . MSB_VERSION . MSB_NL;
            $value .= '#' . MSB_NL;
            $value .= '# Host: ' . $this->server . MSB_NL;
            $value .= '# Generated: ' . date('M j, Y') . ' at ' . date('H:i') . MSB_NL;
            $value .= '# MySQL version: ' . mysql_get_server_info() . MSB_NL;
            $value .= '# PHP version: ' . phpversion() . MSB_NL;
            if (!empty($this->database)) {
                $value .= '#' . MSB_NL;
                $value .= '# Database: `' . $this->database . '`' . MSB_NL;
            }
            $value .= '#' . MSB_NL . MSB_NL . MSB_NL;
        }
        if (!($tables = $this->_GetTables())) {
            return false;
        }
        foreach ($tables as $table)
        {
            if (!($table_dump = $this->_DumpTable($table))) {
                $this->error = mysql_error();
                return false;
            }
            $value .= $table_dump;
        }
        return $value;
    }


    private function _SaveToFile($fname, $sql)
    {
        if ($this->compress)
        {
            if (!($zf = gzopen($fname, 'w9'))) {
                $this->error = 'Can\'t create the output file. Make sure the path exists and is writable.';
                return false;
            }
            gzwrite($zf, $sql);
            gzclose($zf);
        }
        else
        {
            if (!($f = fopen($fname, 'w'))) {
                $this->error = 'Can\'t create the output file. Make sure the path exists and is writable.';
                return false;
            }
            fwrite($f, $sql);
            fclose($f);
        }
        return ($fname);
    }


    function _DownloadFile($fname, $sql)
    {
        header('Content-disposition: filename=' . $fname);
        header('Content-type: application/octetstream');
        header('Pragma: no-cache');
        header('Expires: 0');
        echo ($this->compress ? gzencode($sql) : $sql);
        return true;
    }

}