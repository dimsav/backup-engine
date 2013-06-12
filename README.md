php-mysql-backup
================

A php script used to backup your project's files and mysql databases. The backups are archived as zip
files, and sent to an dropbox account.

This script is ideal to run cronjob backups.

## Features

* Multiple projects can be backed up at once
* Backup of multiple directories per project
* Backup of multiple databases per project
* Password protection of backup files (.zip)
* Detailed logs

## Requirements

This script can only be used in Unix systems (Linux, Mac), as we are using the zip command of the system.

## Instructions

1. Edit config.php to define your projects to be backed up
2. run cron.php using command line or web server