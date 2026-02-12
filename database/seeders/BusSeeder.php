<?php
namespace Database\Seeders;

use App\Enums\OperatorType;
use App\Models\Bus;
use App\Models\Operator;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        $time = now();

        $operators = [
            [
                'name'       => 'KSRTC',
                'type'       => OperatorType::STATE,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name'       => 'MRL Pvt Ltd.',
                'type'       => OperatorType::PRIVATE,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name'       => 'Shastha Pvt Ltd.',
                'type'       => OperatorType::PRIVATE,
                'created_at' => $time,
                'updated_at' => $time,
            ],
        ];

        Operator::insert($operators);

        $buses = [
            [
                'operator_id' => Operator::where('name', 'KSRTC')->first()->id,
                'bus_number'  => 'KL 15 A 1022',
                'bus_name'    => 'KSRTC',
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'MRL Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 A 1023',
                'bus_name'    => 'MRL',
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'Shastha Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 A 1024',
                'bus_name'    => 'Shastha',
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
        ];

        Bus::insert($buses);
    }
}
