<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class start extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

/// composer require predis/predis:^2.0
/// php artisan install:api
///composer require pusher/pusher-php-server
//npm install --save laravel-echo pusher-js
///
///
///
///

        Artisan::call('migrate:fresh --seed');
        Artisan::call('queue:work');
    }
}
