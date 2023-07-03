<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class dev extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'setting dev environment';


     /**
     * The Charlie logo.
     *
     * @var string
     */
    protected $asciiLogo = "
        ________  _            _        ________     _            _    ________
      |   _____| | |          | |      |  _____ |   | |          | [  |  ______|
      |  |       | |____     | - |     | |______|   | |          | |  | |______
      |  |       |  __  |   | | | |    | | |_|_     | |          | |  | _______|
      |  |_____  | |  | |  | |   | |   | |   |_|_   | |________  | |  | |______
      |________| |_|  |_| |__    |__|  [ |     |_|  |__________| |_|  |________|
     ";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = microtime(true);

        //character encoding
        ini_set('output_encoding', 'UTF-8');

        $this->info("\033[0;94m ".$this->asciiLogo);

        $this->info("\033[1;36m DEV ENVIRONMENT CREATION");

        //setting up the project
        $this->basicSetUp();

        $this->info("\033[35m Action completed in ".round((microtime(true) - $start), 4)." seconds");
    }

         /**
     * Execute the basic set up.
     */
    public function basicSetUp(){
        //optimize
        $this->info("\033[0;33m Starting optimization...");
        Artisan::call('clear-compiled');
        Artisan::call('optimize:clear');
        $this->info("\033[32m Optimization done \xE2\x9C\x94");

        //db wipe
        $this->info("\033[33m Starting db wipe...");
        Artisan::call('db:wipe');
        $this->info("\033[32m DB wipe done \xE2\x9C\x94");

        //migrate
        $this->info("\033[33m Starting migrating...");
        Artisan::call('migrate:fresh');
        $this->info("\033[32m Default migration done \xE2\x9C\x94");

        //seed
        $this->info("\033[33m Starting seeding...");
        Artisan::call('db:seed');
        $this->info("\033[32m Seeding done \xE2\x9C\x94");
    }
}
