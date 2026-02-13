<?php
namespace App\Imports;

use App\Models\City;
use App\Models\District;
use App\Models\State;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CitiesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $states    = State::pluck('id', 'code')->toArray();
        $districts = District::all()
            ->groupBy('state_id')
            ->map(function ($items) {return $items->pluck('id', 'name');})
            ->toArray();

        DB::transaction(function () use ($rows, $states, $districts) {

            foreach ($rows as $row) {
                if (! $row['state_code'] || ! $row['district_name'] || ! $row['name']) {
                    continue;
                }

                if (! isset($states[$row['state_code']])) {
                    continue;
                }
                $stateId = $states[$row['state_code']];

                if (! isset($districts[$stateId][$row['district_name']])) {
                    continue;
                }
                $districtId = $districts[$stateId][$row['district_name']];

                City::updateOrCreate(['district_id' => $districtId, 'name' => trim($row['name'])], []);
            }
        });
    }
}
