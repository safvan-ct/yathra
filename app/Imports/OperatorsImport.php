<?php
namespace App\Imports;

use App\Enums\OperatorAuthStatus;
use App\Models\Operator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OperatorsImport implements ToModel, WithHeadingRow
{
    protected int $offset           = 0;
    protected bool $isFirstRow      = true;
    protected int $routeDirectionId = 0;

    public function model(array $row)
    {
        $duplicate = Operator::where('phone', $row['phone'])->exists();

        if ($duplicate) {
            return null;
        }

        return Operator::updateOrCreate(['phone' => $row['phone']], [
            'name'        => $row['name'],
            'type'        => $row['type'],
            'pin'         => $row['pin'],
            'auth_status' => OperatorAuthStatus::APPROVED,
        ]);
    }
}
