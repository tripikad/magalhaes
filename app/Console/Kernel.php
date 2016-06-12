<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\SourceAirports::class,
        \App\Console\Commands\SourceAirports2::class,
        \App\Console\Commands\SourceAirports3::class,
        \App\Console\Commands\SourceCountries1::class,
        \App\Console\Commands\SourceCountries2::class,
        \App\Console\Commands\SourceCountriesEki::class,
        \App\Console\Commands\SourceTeleportCountries::class,

        \App\Console\Commands\SourceAll::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
