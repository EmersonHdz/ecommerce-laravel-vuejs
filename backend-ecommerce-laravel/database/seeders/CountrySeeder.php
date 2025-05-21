<?php

namespace Database\Seeders;


namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Constraint\Count;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $ukCitiesAndRegions = [
    // Regions important
    "ENG" => 'England',
    "SCT" => 'Scotland', 
    "WLS" => 'Wales',
    "NIR" => 'Northern Ireland',
    
    // citys uk
    "LON" => 'London',
    "BIR" => 'Birmingham',
    "MAN" => 'Manchester',
    "LIV" => 'Liverpool',
    "LEE" => 'Leeds',
    "NEW" => 'Newcastle upon Tyne',
    "SHE" => 'Sheffield',
    "BRI" => 'Bristol',
    "NOT" => 'Nottingham',
    "LEI" => 'Leicester'
];

$countries = [
    ['code' => 'geo', 'name' => 'Georgia', 'states' => null],
    ['code' => 'ind', 'name' => 'India', 'states' => null],
    ['code' => 'gbr', 'name' => 'United Kingdom', 'states' => json_encode($ukCitiesAndRegions)],
    ['code' => 'ger', 'name' => 'Germany', 'states' => null],
];
        Country::insert($countries);
    }
}
