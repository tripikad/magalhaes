<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use ZipArchive;
use GuzzleHttp\Client as Guzzle;
use League\Csv\Reader;
use Goutte\Client as Goutte;

abstract class Source extends Command
{

    protected $csvreader;
    protected $goutte;
    protected $guzzle;
    protected $ziparchive;

    public function __construct()
    {
        
        parent::__construct();

        $this->guzzle = new Guzzle();
        $this->ziparchive = new ZipArchive();

    }

    public function crawler($sourceurl)
    {

        return (new Goutte())->request('GET', $sourceurl);

    }

    public function csv($csv)
    {

        return Reader::createFromString($csv);

    }

    public function cleanSource($sourcename)
    {

        $this->line("\nCleaning $sourcename previous data");

        app('db')->table('source')->where('sourcename', '=', $sourcename)->delete();

    }

    public function fetchJson($sourceurl, $associative = false) {

        $this->line('Downloading source');

        $this->info($sourceurl);

        return json_decode(file_get_contents($sourceurl), $associative);

    }

    public function fetchXML($sourceurl, $associative = false) {

        $this->line('Downloading source');

        $this->info($sourceurl);

        return json_decode(
            json_encode(
                simplexml_load_string(
                    file_get_contents($sourceurl)
                )
            ), $associative);
    
    }

}