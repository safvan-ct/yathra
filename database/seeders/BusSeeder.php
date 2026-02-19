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
                'phone'      => '1234567890',
                'pin'        => '1234',
                'type'       => OperatorType::STATE,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name'       => 'MRL Pvt Ltd.',
                'phone'      => '1234567891',
                'pin'        => '1234',
                'type'       => OperatorType::PRIVATE,
                'created_at' => $time,
                'updated_at' => $time,
            ],
            [
                'name'       => 'Pvt Ltd.',
                'phone'      => '1234567892',
                'pin'        => '1234',
                'type'       => OperatorType::PRIVATE,
                'created_at' => $time,
                'updated_at' => $time,
            ]
        ];

        Operator::insert($operators);

        $buses = [
            [
                'operator_id' => Operator::where('name', 'KSRTC')->first()->id,
                'bus_number'  => 'KL 15 K 1022',
                'bus_name'    => 'KSRTC',
                'bus_color'   => 'white',
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 F 1025',
                'bus_name'    => 'Fathima',
                'bus_color'   => 'info',
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'MRL Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 M 1023',
                'bus_name'    => 'MRL',
                'bus_color'   => 'info',
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 S 1024',
                'bus_name'    => 'Shastha',
                'bus_color'   => 'info',
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 G 1026',
                'bus_name'    => 'Gazal',
                'bus_color'   => 'info',
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 MD 1027',
                'bus_name'    => 'Madheena',
                'bus_color'   => 'info',
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 A 1028',
                'bus_name'    => 'Arya Mol',
                'bus_color'   => 'danger',
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 AL 1029',
                'bus_name'    => 'Al-Ameen',
                'bus_color'   => 'info',
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 SK 1030',
                'bus_name'    => 'Sundharikutty',
                'bus_color'   => 'info',
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
        ];

        Bus::insert($buses);
    }
}
