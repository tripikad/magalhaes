<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceAll extends Command
{

    protected $signature = 'source:all';

    public function handle()
    {

        $this->call('source:airports_1');
        $this->call('source:airports_2');
        $this->call('source:airports_3');

        $this->call('source:countries_1');
        $this->call('source:countries_2');
        $this->call('source:countries_eki');
        $this->call('source:countries_teleport');

    }

}