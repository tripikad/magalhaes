<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TargetCountries extends Target
{

    protected $signature = 'target:countries';


    public function handle()
    {
        
        $countries_unknown = [

            'Angoola' => 'AO',
            'Aserbaidžaan' => 'AZ',
            'Bahama' => 'BS',
            'Bosnia ja Hertsegoviina' => 'BA',
            'Elevandiluurannik' => 'CI',
            'Fidži' => 'FJ',
            'Haiti' => 'HT',
            'Holland' => 'NL',
            'Hollandi Antillid' => 'AN',
            'Ida-Timor' => 'TL',
            'Iirimaa' => 'IE',
            'Jamaika' => 'JM',
            'Jordaania' => 'JO',
            'Kambodža' => 'KH',
            'Kesk-Aafrika Vabariik' => 'CF',
            'Kolumbia' => 'CO',
            'Komoorid' => 'KM',
            'Kongo Demokraatlik Vabariik' => 'CD',
            'Kongo' => 'CG',
            'Kookosesaared' => 'CC',
            'Kosovo' => 'XK',
            'Kõrgõzstan' => 'KG',
            'Küpros' => 'CY',
            'Laos' => 'LA',
            'Lõuna-Aafrika Vabariik' => 'ZA',
            'Lõuna-Korea' => 'KR',
            'Makedoonia' => 'MK',
            'Marshalli saared' => 'MH',
            'Mikroneesia' => 'FM',
            'Myanmar' => 'MM',
            'Paapua Uus-Guinea' => 'PG',
            'Palau' => 'PW',
            'Paraguai' => 'PY',
            'Reunion' => 'RE',
            'Saalomoni saared' => 'SB',
            'Saint Vincent ja Grenadiinid' => 'VC',
            'Saint-Pierre ja Miquelon' => 'PM',
            'Sambia' => 'ZM',
            'Sao Tome ja Principe' => 'ST',
            'Seišellid' => 'SC',
            'Surinam' => 'SR',
            'Suurbritannia' => 'GB',
            'Tadžikistan' => 'TJ',
            'Taivan' => 'TW',
            'Tansaania' => 'TZ',
            'Tšaad' => 'TD',
            'Tšehhi' => 'CZ',
            'Tšiili' => 'CL',
            'Uruguai' => 'UY',
            'USA' => 'US',
            'Uus-Meremaa' => 'NZ',
            'Vatikan' => 'VA',
            'Šveits' => 'CH',
            //'Chennai' => '' // City
            //'Galapagos' => '', // Not country
            //'Havai' => '', // Not country
            //'Jugoslaavia' => '',
            //'Kanaari saared' => '', // Not country
            //'Somaalimaa' => '', // Not country
        ];

        app('db')->table('target_countries')->truncate();
        
        // First we get the continents, destinations without parent id

        $continentIds = $this->getSource('countries_trip')
            ->filter(function($country) {
                return $country->parent_id == null;
            })
            ->lists('id')
            ->all();

        // For each country try to detect its country code,
        // either by English (mledoze) or Estonian name (geonames)
        // If no match is found, fall back to $countries_unknown map

        $countries = $this->getSource('countries_trip');

        $this->output->progressStart($countries->count());

        $countries->filter(function($country) use ($continentIds) {
      
                return in_array($country->parent_id, $continentIds);
      
            })
            ->map(function($country) {

                $iso2 = collect(app('db')
                    ->table('source')
                    ->where('sourcename', '=', 'countries_mledoze')
                    ->where('value->name->common', $country->name)
                    ->lists('value')
                )->map('json_decode')
                ->lists('cca2')
                ->first();

                $country->iso2 = $iso2 ?? null;
                
                return $country;

            })
            ->map(function($country) {

                $iso2 = collect(app('db')
                    ->table('source')
                    ->where('sourcename', '=', 'countries_geonames')
                    ->where('value->countryName', $country->name)
                    ->lists('value')
                )->map('json_decode')
                ->lists('countryCode')
                ->first();

                $country->iso2 = $iso2 ?? null;
                
                $this->output->progressAdvance();

                return $country;

            })
            ->map(function($country) use ($countries_unknown) {

                if (in_array($country->name, array_keys($countries_unknown))) {
                    $country->iso2 = $countries_unknown[$country->name];
                }
            
                return $country;
            
            })->each(function($country) {

                app('db')
                    ->table('target_countries')
                    ->insert([
                        'name_et' => $country->name,
                        'iso2' => $country->iso2,
                        'id_legacy' => $country->id
                    ]);

            });

            $this->output->progressFinish();

    }
    
}
