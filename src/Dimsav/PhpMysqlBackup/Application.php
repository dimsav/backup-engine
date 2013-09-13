<?php namespace Dimsav\PhpMysqlBackup;


class Application {

    public function run()
    {
        $this->setup();
    }

    private function setup()
    {
        if (Config::get('app.debug', true))
        {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        }

        date_default_timezone_set(Config::get('app.timezone', 'Europe/Berlin'));

        set_time_limit (Config::get('app.time_limit', 0));
    }
}