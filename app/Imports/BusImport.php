<?php
namespace App\Imports;

use App\Enums\BusAuthStatus;
use App\Enums\OperatorAuthStatus;
use App\Models\Bus;
use App\Models\Operator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BusImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $operator = Operator::where('id', $row['operator_id'])->exists();

        $slug      = strtolower(preg_replace('/\s+/', '', $row['bus_number']));
        $duplicate = Bus::where('slug', $slug)->exists();

        if ($duplicate || ! $operator) {
            return null;
        }

        return Bus::updateOrCreate(['slug' => $slug], [
            'operator_id' => $row['operator_id'],
            'bus_name'    => $row['bus_name'],
            'bus_number'  => $row['bus_number'],
            'bus_color'   => $row['bus_color'],
            'auth_status' => BusAuthStatus::APPROVED,
        ]);
    }
}
