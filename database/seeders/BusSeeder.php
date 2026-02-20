<?php
namespace Database\Seeders;

use App\Enums\BusAuthStatus;
use App\Imports\OperatorsImport;
use App\Models\Bus;
use App\Models\Operator;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        $time = now();

        $operators = database_path('seeders/files/operators.txt');
        Excel::import(new OperatorsImport(), $operators);

        $buses = [
            [
                'operator_id' => Operator::where('name', 'KSRTC')->first()->id,
                'bus_number'  => 'KL 15 K 1022',
                'slug'        => strtolower(preg_replace('/\s+/', '', 'KL 15 K 1022')),
                'bus_name'    => 'KSRTC',
                'bus_color'   => 'white',
                'auth_status' => BusAuthStatus::APPROVED,
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 F 1025',
                'slug'        => strtolower(preg_replace('/\s+/', '', 'KL 50 F 1025')),
                'bus_name'    => 'Fathima',
                'bus_color'   => 'info',
                'auth_status' => BusAuthStatus::APPROVED,
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'MRL Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 M 1023',
                'slug'        => strtolower(preg_replace('/\s+/', '', 'KL 50 M 1023')),
                'bus_name'    => 'MRL',
                'bus_color'   => 'info',
                'auth_status' => BusAuthStatus::APPROVED,
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 S 1024',
                'slug'        => strtolower(preg_replace('/\s+/', '', 'KL 50 S 1024')),
                'bus_name'    => 'Shastha',
                'bus_color'   => 'info',
                'auth_status' => BusAuthStatus::APPROVED,
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 G 1026',
                'slug'        => strtolower(preg_replace('/\s+/', '', 'KL 50 G 1026')),
                'bus_name'    => 'Gazal',
                'bus_color'   => 'info',
                'auth_status' => BusAuthStatus::APPROVED,
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 MD 1027',
                'slug'        => strtolower(preg_replace('/\s+/', '', 'KL 50 MD 1027')),
                'bus_name'    => 'Madheena',
                'bus_color'   => 'info',
                'auth_status' => BusAuthStatus::APPROVED,
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 A 1028',
                'slug'        => strtolower(preg_replace('/\s+/', '', 'KL 50 A 1028')),
                'bus_name'    => 'Arya Mol',
                'bus_color'   => 'danger',
                'auth_status' => BusAuthStatus::APPROVED,
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 AL 1029',
                'slug'        => strtolower(preg_replace('/\s+/', '', 'KL 50 AL 1029')),
                'bus_name'    => 'Al-Ameen',
                'bus_color'   => 'info',
                'auth_status' => BusAuthStatus::APPROVED,
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
            [
                'operator_id' => Operator::where('name', 'Pvt Ltd.')->first()->id,
                'bus_number'  => 'KL 50 SK 1030',
                'slug'        => strtolower(preg_replace('/\s+/', '', 'KL 50 SK 1030')),
                'bus_name'    => 'Sundharikutty',
                'bus_color'   => 'info',
                'auth_status' => BusAuthStatus::APPROVED,
                'created_at'  => $time,
                'updated_at'  => $time,
            ],
        ];

        Bus::insert($buses);
    }
}
