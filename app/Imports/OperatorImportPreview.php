<?php
namespace App\Imports;

use App\Enums\OperatorType;
use App\Models\Operator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OperatorImportPreview implements ToCollection, WithHeadingRow
{
    public array $preview = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            $valid   = true;
            $message = '';

            $duplicate = Operator::where('phone', $row['phone'])->exists();

            if ($duplicate) {
                $message .= 'Duplicate operator found,';
                $valid    = false;
            }

            if (! in_array($row['pin'], range(1000, 9999))) {
                $message .= 'Invalid pin,';
                $valid    = false;
            }

            if (! OperatorType::tryFrom($row['type'])) {
                $message .= 'Invalid type,';
                $valid    = false;
            }

            $this->preview[] = [
                'name'    => $row['name'],
                'phone'   => $row['phone'],
                'pin'     => $row['pin'],
                'type'    => $row['type'],
                'valid'   => $valid,
                'message' => $message,
            ];
        }
    }
}
