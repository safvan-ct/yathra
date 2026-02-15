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

        $directionStops = database_path('seeders/files/route-direction-stops.txt');
        Excel::import(new RouteDirectionStopImport(), $directionStops);
    }
}
