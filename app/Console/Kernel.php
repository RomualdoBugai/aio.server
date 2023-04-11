<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\Scheduling',
        'App\Console\Commands\App',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $data = date("Y-m-d H:i:s");
            echo shell_exec("echo \"{$data}\" > /var/www/html/aio.git/schedule.log");
        })->everyMinute();

        /*
        $schedule->call(function () {

            $value = config('database.connections.pgsql');

            extract( $value );

            $path   = public_path() . "/" . "storage" . "/" . "database" . "/";
            $script = $path .  "script.sh";
            $file   = $path . date("YmdHi") . ".backup";

            #$string = "#!/bin/bash\npg_dump -d {$database} -h {$host} -p {$port} -U {$username} -w -f {$file}";
            $string = "pg_dump -d {$database} -h {$host} -p {$port} -U {$username} -w -f {$file}";

            $myfile = fopen($script, "w") or die("Unable to open file!");
            fwrite($myfile, $string);
            fclose($myfile);

            echo file_get_contents($script);

        })->everyMinute();
        */


    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
