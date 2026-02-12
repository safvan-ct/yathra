<?php
namespace Database\Seeders;

use App\Models\RoutePattern;
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
                'Name'                => 'AMBALAPPARA to MKD',
                'origin_stop_id'      => Stop::where('name', 'Ambalappara')->first()->id,
                'destination_stop_id' => Stop::where('name', 'Nellippuzha')->first()->id,
                'created_at'          => $time,
                'updated_at'          => $time,
            ],
            [
                'Name'                => 'MKD to AMBALAPPARA',
                'origin_stop_id'      => Stop::where('name', 'Nellippuzha')->first()->id,
                'destination_stop_id' => Stop::where('name', 'Ambalappara')->first()->id,
                'created_at'          => $time,
                'updated_at'          => $time,
            ],
            [
                'Name'                => 'TVK to MKD',
                'origin_stop_id'      => Stop::where('name', 'Thiruvizhamkunnu')->first()->id,
                'destination_stop_id' => Stop::where('name', 'Nellippuzha')->first()->id,
                'created_at'          => $time,
                'updated_at'          => $time,
            ],
            [
                'Name'                => 'MKD to TVK',
                'origin_stop_id'      => Stop::where('name', 'Nellippuzha')->first()->id,
                'destination_stop_id' => Stop::where('name', 'Thiruvizhamkunnu')->first()->id,
                'created_at'          => $time,
                'updated_at'          => $time,
            ],
        ];

        foreach ($routes as $route) {
            RoutePattern::create($route);
        }
    }
}
