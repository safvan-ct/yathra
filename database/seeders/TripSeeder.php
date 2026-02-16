<?php
namespace Database\Seeders;

use App\Imports\TripSchedulesImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $trips = database_path('seeders/files/trips.txt');
        Excel::import(new TripSchedulesImport, $trips);
    }
}
