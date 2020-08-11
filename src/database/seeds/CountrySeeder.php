<?php

use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            ['region_code' => 'gb', 'wikipedia_title' => 'United_Kingdom', 'name' => 'United Kingdom'],
            ['region_code' => 'nl', 'wikipedia_title' => 'Netherlands', 'name' => 'Netherlands'],
            ['region_code' => 'de', 'wikipedia_title' => 'Denmark', 'name' => 'Denmark'],
            ['region_code' => 'fr', 'wikipedia_title' => 'France', 'name' => 'France'],
            ['region_code' => 'es', 'wikipedia_title' => 'Spain', 'name' => 'Spain'],
            ['region_code' => 'it', 'wikipedia_title' => 'Italy', 'name' => 'Italy'],
            ['region_code' => 'gr', 'wikipedia_title' => 'Greece', 'name' => 'Greece'],
        ];

        \App\Country::insert($countries);
    }
}
