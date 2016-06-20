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

        \App\Console\Commands\SourceAirlinesOpenflights::class,
        \App\Console\Commands\SourceAirportsJbrook::class,
        \App\Console\Commands\SourceAirportsMwgg::class,
        \App\Console\Commands\SourceAirportsOpenflights::class,
        \App\Console\Commands\SourceAirportsRam::class,
        \App\Console\Commands\SourceAll::class,
        \App\Console\Commands\SourceCountriesEki::class,
        \App\Console\Commands\SourceCountriesGeonames::class,
        \App\Console\Commands\SourceCountriesMledoze::class,
        \App\Console\Commands\SourceCountriesTeleport::class,
        \App\Console\Commands\SourceCountriesTrip::class,
        \App\Console\Commands\TargetCountries::class,
        
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
