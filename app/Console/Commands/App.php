<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class App extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        switch ($this->argument('action')) {

            case 'cache:clear':
                return \Cache::flush();
                echo "app cache cleared";
                break;

            case 'config:clear':
                \Artisan::call('config:clear');
                echo \Artisan::output();
                break;

            case 'migrate':
                \Artisan::call('migrate');
                echo \Artisan::output();
                break;

            case 'check:certificate':
                echo "Executando serviço de validação de data de expiração dos certificados cadastrados.";
                ## comando que vai pushar a parada 
                echo \Artisan::output();
                break;

            case 'backup:pgsql':

                $value = config('database.connections.pgsql');

                extract( $value );

                $path   = public_path() . "/" . "storage" . "/" . "database" . "/";
                $script = $path .  "script.sh";
                $file   = $path . date("YmdHi") . ".backup";

                $string = "#!/bin/bash\nPGPASSWORD=\"postgres\" pg_dump -d {$database} -h {$host} -p {$port} -U {$username} -w -f {$file}";

                $myfile = fopen($script, "w") or die("Unable to open file!");
                fwrite($myfile, $string);
                fclose($myfile);

                echo file_get_contents($script) . "\n";
                echo shell_exec( "sh " . $script );

                break;

            default:
                echo "action not found";
                break;
        }
    }
}
