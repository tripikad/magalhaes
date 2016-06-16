<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

abstract class Target extends Command
{

    protected $csvreader;
    protected $goutte;
    protected $guzzle;
    protected $ziparchive;

    public function __construct()
    {
        
        parent::__construct();

    }

    public function getSource($sourcename)
    {
        
        return collect(
            app('db')
                ->table('source')
                ->where('sourcename', '=', $sourcename)
                ->lists('value')
            )
            ->map(function($item) {
                    return (object) json_decode($item); 
                }
            );
    
    }

}