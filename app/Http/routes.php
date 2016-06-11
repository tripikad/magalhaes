<?php

$app->get('/', function () {

    return file_get_contents(storage_path() . '/app/index.html');

});

$app->get('/sources', function () {

    $sources = collect([

        [
            'name' => 'World Airports (by jbrooksuk)',
            'sourcename' => 'airports',
            'source_url' => 'https://github.com/jbrooksuk/JSON-Airports',
            'map' => true,
        ],
        [
            'name' => 'World Airports (by ram-nadella)',
            'sourcename' => 'airports2',
            'source_url' => 'https://github.com/ram-nadella/airport-codes',
            'map' => true,
        ],
        [
            'name' => 'World Airports (by mwgg)',
            'sourcename' => 'airports3',
            'source_url' => 'https://github.com/mwgg/Airports/airport-codes',
            'map' => true,
        ],
        [
            'name' => 'Countries (by mledoze)',
            'sourcename' => 'countries1',
            'source_url' => 'https://github.com/mledoze/countries',
            'map' => true,
        ],
        [
            'name' => 'Countries (by Geonames)',
            'sourcename' => 'countries2',
            'source_url' => 'http://api.geonames.org/countryInfo',
        ]
  
    ])->map(function($source) {

        $source['count'] = app('db')
            ->table('sources')
            ->where('sourcename', $source['sourcename'])
            ->count();

        $source['data'] = [];
        $source['sample_count'] = 50;

        return $source;
   
    });

    return response()->json($sources);

});

$app->get('/sources/{sourcename}', function ($sourcename) {
    
    $results = app('db')
        ->table('sources')
        ->where('sourcename', $sourcename)
        ->take(50)
        ->orderByRaw('RAND()')
        ->lists('value');

    return response()->json(
        collect($results)
            ->map(function($item) { return json_decode($item); })
            ->all()
    );
    
});



