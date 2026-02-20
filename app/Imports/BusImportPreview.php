<?php
namespace App\Imports;

use App\Models\Bus;
use App\Models\Operator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BusImportPreview implements ToCollection, WithHeadingRow
{
    public array $preview = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            $valid   = true;
            $message = '';

            $operator = Operator::select('id', 'name')->where('id', $row['operator_id'])->first();

            if (! $operator) {
                $message .= 'Operator found,';
                $valid    = false;
            }

            $slug      = strtolower(preg_replace('/\s+/', '', $row['bus_number']));
            $duplicate = Bus::where('slug', $slug)->exists();

            if ($duplicate) {
                $message .= 'Duplicate, bus number exists,';
                $valid    = false;
            }

            $this->preview[] = [
                'name'     => $row['bus_name'],
                'operator' => $operator?->name,
                'number'   => $row['bus_number'],
                'color'    => $row['bus_color'],
                'valid'    => $valid,
                'message'  => $message,
            ];
        }
    }
}
