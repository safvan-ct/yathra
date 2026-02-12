<?php
namespace Database\Seeders;

use App\Enums\TripServiceType;
use App\Models\Bus;
use App\Models\RoutePattern;
use App\Models\Stop;
use App\Models\Trip;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $time = now();

        $trips = [
            [
                'route_pattern_id' => RoutePattern::where('name', 'TVK to MKD')->first()->id,
                'bus_id'           => Bus::Where('bus_number', 'KL 50 A 1023')->first()->id,
                'start_time'       => '08:15:00',
                'final_stop_id'    => Stop::where('name', 'Nellippuzha')->first()->id,
                'service_type'     => TripServiceType::ORDINARY,
                'created_at'       => $time,
                'updated_at'       => $time,
            ],
        ];

        Trip::insert($trips);
    }
}
