<?php
namespace Database\Seeders;

use App\Models\RoutePattern;
use App\Models\RoutePatternStop;
use App\Models\Stop;
use Illuminate\Database\Seeder;

class RoutePatternSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $time = now();

        $routes = [
            [
                'name'                => 'AP to MKD',
                'info'                => 'Ambalappara to Mannarkkad',
                'origin_stop_id'      => Stop::where('name', 'Ambalappara')->first()->id,
                'destination_stop_id' => Stop::where('name', 'Nellippuzha')->first()->id,
                'created_at'          => $time,
                'updated_at'          => $time,
            ],
            [
                'name'                => 'TVK to MKD',
                'info'                => 'Thiruvizhamkunnu to Mannarkkad',
                'origin_stop_id'      => Stop::where('name', 'Thiruvizhamkunnu')->first()->id,
                'destination_stop_id' => Stop::where('name', 'Nellippuzha')->first()->id,
                'created_at'          => $time,
                'updated_at'          => $time,
            ],
        ];

        foreach ($routes as $route) {
            RoutePattern::create($route);
        }

        $routeStopes = [
            [
                'route_pattern_id'       => RoutePattern::where('name', 'AMBALAPPARA to MKD')->first()->id,
                'stop_id'                => Stop::where('name', 'Ambalappara')->first()->id,
                'stop_order'             => 1,
                'default_offset_minutes' => 0,
            ],
            [
                'route_pattern_id'       => RoutePattern::where('name', 'AMBALAPPARA to MKD')->first()->id,
                'stop_id'                => Stop::where('name', 'Thiruvizhamkunnu')->first()->id,
                'stop_order'             => 2,
                'default_offset_minutes' => 10,
            ],
            [
                'route_pattern_id'       => RoutePattern::where('name', 'AMBALAPPARA to MKD')->first()->id,
                'stop_id'                => Stop::where('name', 'Nellippuzha')->first()->id,
                'stop_order'             => 3,
                'default_offset_minutes' => 20,
            ],

            [
                'route_pattern_id'       => RoutePattern::where('name', 'TVK to MKD')->first()->id,
                'stop_id'                => Stop::where('name', 'Thiruvizhamkunnu')->first()->id,
                'stop_order'             => 1,
                'default_offset_minutes' => 0,
            ],
            [
                'route_pattern_id'       => RoutePattern::where('name', 'TVK to MKD')->first()->id,
                'stop_id'                => Stop::where('name', 'Nellippuzha')->first()->id,
                'stop_order'             => 2,
                'default_offset_minutes' => 10,
            ],
        ];

        RoutePatternStop::insert($routeStopes);
    }
}
