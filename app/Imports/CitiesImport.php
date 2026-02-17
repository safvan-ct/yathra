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
        $states = State::pluck('id', 'code')->toArray();

        $districts = District::all()
            ->groupBy('state_id')
            ->map(function ($items) {return $items->pluck('id', 'code');})
            ->toArray();

        DB::transaction(function () use ($rows, $states, $districts) {

            $cities = [];
            foreach ($rows as $row) {
                if (! $row['state_code'] || ! $row['district_code'] || ! $row['city_name'] || ! $row['city_code']) {
                    continue;
                }

                if (! isset($states[$row['state_code']])) {
                    continue;
                }
                $stateId = $states[$row['state_code']];

                if (! isset($districts[$stateId][$row['district_code']])) {
                    continue;
                }
                $districtId = $districts[$stateId][$row['district_code']];

                $cities[] = [
                    'district_id' => $districtId,
                    'name'        => trim($row['city_name']),
                    'code'        => $row['city_code'],
                ];
            }

            City::upsert($cities, ['district_id', 'name']);
        });
    }
}
