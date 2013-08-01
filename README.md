# Website backup library (PHP, MySQL, Dropbox)

This is a library, written in php, used to backup your project's files and mysql databases. It is written to run from a
web server as well as from the command line (cronjobs).

The backups are saved as zip files in the server and sent to your dropbox account (optional).

## Features

* Multiple projects can be backed up at once
* Custom selection of backup directories per project
* Custom selection of excluded paths
* Password protection of backup files (.zip)
* Detailed logs

## Requirements

1. This script can only be used in Unix systems (Linux/Mac), as we are using the zip command of the system.
2. The function exec() should be available as we use it to zip our backups.
3. The cURL extension is required if you want to use the dropbox uploader.

## Instructions

1. Copy config.ini.php to config.php.
2. Edit config.php to define your projects to be backed up.
3. Run cron.php using the command line or a web server.
4. See the magic happen!