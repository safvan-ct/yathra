<?php
namespace Database\Seeders;

use App\Imports\RouteDirectionImport;
use App\Imports\RouteDirectionStopImport;
use App\Imports\RoutePatternImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class RoutePatternSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $routes = database_path('seeders/files/route.txt');
        Excel::import(new RoutePatternImport(), $routes);

        $directions = database_path('seeders/files/route-directions.txt');
        Excel::import(new RouteDirectionImport(), $directions);

        for ($i = 1; $i <= 4; $i++) {
            $directionStops = database_path('seeders/files/route-stops/' . $i . '.txt');
            Excel::import(new RouteDirectionStopImport(), $directionStops);
        }
    }
}
