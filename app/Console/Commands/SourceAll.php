<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceAll extends Command
{

    protected $signature = 'source:all';

    public function handle()
    {

        $this->call('source:airlines_openflights');
        $this->call('source:airports_jbrook');
        $this->call('source:airports_mwgg');
        $this->call('source:airports_openflights');
        $this->call('source:airports_ram');
        $this->call('source:countries_eki');
        $this->call('source:countries_geonames');
        $this->call('source:countries_mledoze');
        $this->call('source:countries_teleport');
        $this->call('source:countries_trip');
        $this->call('source:routes_openflights');

    }

}