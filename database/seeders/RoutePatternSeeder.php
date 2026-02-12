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
                'destination_stop_id' => Stop::where('name', 'MKD KSRTC Station')->first()->id,
                'created_at'          => $time,
                'updated_at'          => $time,
            ],
            [
                'name'                => 'TVK to MKD',
                'info'                => 'Thiruvizhamkunnu to Mannarkkad',
                'origin_stop_id'      => Stop::where('name', 'Thiruvizhamkunnu')->first()->id,
                'destination_stop_id' => Stop::where('name', 'MKD KSRTC Station')->first()->id,
                'created_at'          => $time,
                'updated_at'          => $time,
            ],
        ];

        foreach ($routes as $route) {
            RoutePattern::create($route);
        }

        $stops       = Stop::all();
        $routeStopes = [];

        $minutes = 0;
        $offset  = 0;

        foreach ($stops as $key => $stop) {
            $minutes = $key == 0 ? 0 : 2;
            $offset = $key == 0 ? 0 : $offset + $minutes;

            $routeStopes[] = [
                'route_pattern_id'           => RoutePattern::where('name', 'AP to MKD')->first()->id,
                'stop_id'                    => $stop->id,
                'stop_order'                 => ++$key,
                'minutes_from_previous_stop' => $minutes,
                'default_offset_minutes'     => $offset,
            ];
        }

        RoutePatternStop::insert($routeStopes);
    }
}
