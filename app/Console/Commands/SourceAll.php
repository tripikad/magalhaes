<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceAll extends Command
{

    protected $signature = 'source:all';

    public function handle()
    {

        $this->call('source:airports1');
        $this->call('source:airports2');
        $this->call('source:airports3');

        $this->call('source:countries1');
        $this->call('source:countries2');
        $this->call('source:countrieseki');
        $this->call('source:countriesteleport');

    }

}