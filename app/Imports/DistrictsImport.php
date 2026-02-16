<?php
namespace App\Imports;

use App\Models\District;
use App\Models\State;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DistrictsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $states = State::pluck('id', 'code')->toArray();

        DB::transaction(function () use ($rows, $states) {

            foreach ($rows as $row) {
                if (! $row['state_code'] || ! $row['district_code'] || ! $row['district_name']) {
                    continue;
                }

                if (! isset($states[$row['state_code']])) {
                    continue;
                }
                $stateId = $states[$row['state_code']];

                District::updateOrCreate(['code' => $row['district_code']], [
                    'name'     => trim($row['district_name']),
                    'state_id' => $stateId,
                ]);
            }
        });
    }
}
