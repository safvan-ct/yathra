<?php
namespace App\Imports;

use App\Models\District;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DistrictsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {

            foreach ($rows as $row) {
                if (! $row['code'] || ! $row['name']) {
                    continue;
                }

                District::updateOrCreate(['code' => $row['code']], [
                    'name'     => trim($row['name']),
                    'state_id' => 1,
                ]);
            }
        });
    }
}
