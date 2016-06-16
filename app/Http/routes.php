<?php

$app->get('sources', 'SourceController@sources');

$app->get('/', function () {

    return file_get_contents(storage_path() . '/app/index.html');

});

$app->get('/source/{sourcename}', function ($sourcename) {
    
    $results = app('db')
        ->table('source')
        ->where('sourcename', $sourcename)
        ->take(50)
//        ->orderByRaw('RAND()')
        ->lists('value');

    return response()->json(
        collect($results)
            ->map(function($item) { return json_decode($item); })
            ->all()
    );
    
});



