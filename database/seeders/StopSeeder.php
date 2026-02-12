<?php
namespace Database\Seeders;

use App\Models\Stop;
use Illuminate\Database\Seeder;

class StopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stops = [
            'Ambalappara',
            'Kaappuparambu Rd.',
            'Thaanikkunnu School Jn.',
            'Irattavaari',
            'Kottakkunnu',
            'Valiyapaara',
            'Thiruvizhamkunnu',
            'Thiruvizhamkunnu P.O',
            'GLPS Thiruvizhamkunnu',
            'Kambanippadi',
            'Maalikkunnu',
            'Bheemanad School Rd.',
            'Paarappuram',
            'Bheemanad Rd.',
            'Kottoppadam School Jn.',
            'Kottoppadam',
            'AB Rd.',
            'Venga',
            'Ariyoor',
            'Kalyanakaappu',
            'Chungam',
            'MES Collage',
            'Kunthippuzha',
            'Kodathippadi',
            'MKD Bus Station',
            'MKD Police Station',
            'MKD Hospital Jn.',
            'MKD KSRTC Station',
        ];

        foreach ($stops as $key => $stop) {
            Stop::create(['name' => $stop, 'code' => 'BWC' . ++$key]);
        }
    }
}
