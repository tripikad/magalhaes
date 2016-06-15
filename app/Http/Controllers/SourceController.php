<?php

namespace App\Http\Controllers;

use Symfony\Component\Yaml\Yaml;

class SourceController extends Controller
{


    public function sources()
    {

       $sources = Yaml::parse(file_get_contents(storage_path() . '/app/sources.yaml'));

        return collect($sources)->map(function($source) {

            $source['count'] = app('db')
                ->table('source')
                ->where('sourcename', $source['sourcename'])
                ->count();

            $source['data'] = [];
            $source['sample_count'] = 50;

            return $source;
   
        });

    }

}
