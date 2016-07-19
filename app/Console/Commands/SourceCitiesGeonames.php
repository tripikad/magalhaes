<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SourceCitiesGeonames extends Source
{

    protected $signature = 'source:cities_geonames';

    public function handle()
    {

        $sourcename = 'cities_geonames';

        $this->cleanSource($sourcename);

        // $sourceurl = 'http://download.geonames.org/export/dump/cities15000.zip';
  
        $sourceurl = 'https://github.com/v5analytics/vertexium/blob/master/examples/geonames-cities15000.txt.gz?raw=true';

        //$this->line('Downloading file');

        //$this->guzzle->get($sourceurl, [
        //    'save_to' => storage_path('app/source/cities15000.zip')
        //]);

       // $this->line('Unzipping');

        //$this->ziparchive->open(storage_path('app/source/cities15000.zip'));
        //$this->ziparchive->extractTo(storage_path('/app/source/'));
        //$this->ziparchive->close();

        $keys = [
            'geonameid', // integer id of record in geonames database
            'name', // name of geographical point (utf8) varchar(200)
            'asciiname', // name of geographical point in plain ascii characters, varchar(200)
            'alternatenames', // alternatenames, comma separated, ascii names automatically transliterated, convenience attribute from alternatename table, varchar(10000)
            'latitude', // latitude in decimal degrees (wgs84)
            'longitude', // longitude in decimal degrees (wgs84)
            'feature_class', // see http://www.geonames.org/export/codes.html, char(1)
            'feature_code', // see http://www.geonames.org/export/codes.html, varchar(10)
            'country_code', // ISO-3166 2-letter country code, 2 characters
            'cc2', // alternate country codes, comma separated, ISO-3166 2-letter country code, 200 characters
            'admin1_code', // fipscode (subject to change to iso code), see exceptions below, see file admin1Codes.txt for display names of this code; varchar(20)
            'admin2_code', // code for the second administrative division, a county in the US, see file admin2Codes.txt; varchar(80) 
            'admin3_code', // code for third level administrative division, varchar(20)
            'admin4_code', // code for fourth level administrative division, varchar(20)
            'population', // bigint (8 byte int) 
            'elevation', // in meters, integer
            'dem', // digital elevation model, srtm3 or gtopo30, average elevation of 3''x3'' (ca 90mx90m) or 30''x30'' (ca 900mx900m) area in meters, integer. srtm processed by cgiar/ciat.
            'timezone', // the timezone id (see file timeZone.txt) varchar(40)
            'modification_date' // date of last modification in yyyy-MM-dd format
        ];


        $csv = $this->csv(
            file_get_contents(storage_path('app/source/cities15000.txt'))
        );
        $csv->setDelimiter("\t");

        $data = $csv->fetchAssoc($keys);

        foreach ($data as $row) {

            app('db')
                ->table('source')
                ->insert([
                    'sourcename' => $sourcename,
                    'value' => json_encode($row)
                ]);

        }

    }

}