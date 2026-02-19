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

        for ($i = 1; $i <= 14; $i++) {
            $cities = database_path('seeders/files/cities/' . $i . '.txt');
            Excel::import(new CitiesImport, $cities);
        }

        $stopPaths = [
            'seeders/files/stops/mkd/mkd-tvk.txt',
            'seeders/files/stops/mkd/mkd-edk.txt',
        ];

        foreach ($stopPaths as $stopPath) {
            Excel::import(new StopsImport, database_path($stopPath));
        }
    }
}
