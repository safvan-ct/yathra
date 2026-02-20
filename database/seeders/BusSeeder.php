<?php
namespace Database\Seeders;

use App\Imports\BusImport;
use App\Imports\OperatorsImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        $operators = database_path('seeders/files/operators.txt');
        Excel::import(new OperatorsImport(), $operators);

        $bus = database_path('seeders/files/buses.txt');
        Excel::import(new BusImport(), $bus);
    }
}
