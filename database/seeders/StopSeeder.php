<?php
namespace Database\Seeders;

use App\Imports\CitiesImport;
use App\Imports\DistrictsImport;
use App\Imports\StopsImport;
use App\Models\State;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class StopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        State::create(['name' => 'Kerala', 'code' => 'KL']);

        $districts = database_path('seeders/files/districts.txt');
        Excel::import(new DistrictsImport, $districts);

        $cities = database_path('seeders/files/cities.txt');
        Excel::import(new CitiesImport, $cities);

        $stops = database_path('seeders/files/stops.txt');
        Excel::import(new StopsImport, $stops);
    }
}
