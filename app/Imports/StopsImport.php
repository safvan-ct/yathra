<?php
namespace App\Imports;

use App\Models\City;
use App\Models\District;
use App\Models\State;
use App\Models\Stop;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StopsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $states = State::pluck('id', 'code')->toArray();

        $districts = District::all()
            ->groupBy('state_id')
            ->map(function ($items) {return $items->pluck('id', 'name');})
            ->toArray();

        $cities = City::all()
            ->groupBy('district_id')
            ->map(function ($items) {return $items->pluck('id', 'name');})
            ->toArray();

        DB::transaction(function () use ($rows, $states, $districts, $cities) {

            foreach ($rows as $row) {
                if (! $row['state_code'] || ! $row['district_name'] || ! $row['city_name'] || ! $row['stop_name']) {
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

                if (! isset($cities[$districtId][$row['city_name']])) {
                    continue;
                }
                $cityId = $cities[$districtId][$row['city_name']];

                $slug = Str::slug(trim($row['stop_name']) . '-' . trim($row['city_name']));

                Stop::updateOrCreate([
                    'name'    => trim($row['stop_name']),
                    'slug'    => $slug,
                    'city_id' => $cityId,
                ], []);
            }
        });
    }
}
