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
            ->map(function ($items) {return $items->pluck('id', 'code');})
            ->toArray();

        $cities = City::all()
            ->groupBy('district_id')
            ->map(function ($items) {return $items->pluck('id', 'code');})
            ->toArray();

        $stop = Stop::select('code')->orderBy('id', 'desc')->first();

        DB::transaction(function () use ($rows, $states, $districts, $cities, $stop) {

            $stops    = [];
            $stopCode = $stop ? explode('-', $stop->code)[1] : 10000;

            foreach ($rows as $row) {
                if (! $row['state_code'] || ! $row['district_code'] || ! $row['city_code'] || ! $row['stop_name']) {
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

                if (! isset($cities[$districtId][$row['city_code']])) {
                    continue;
                }
                $cityId = $cities[$districtId][$row['city_code']];

                $slug = Str::slug(trim($row['stop_name']) . '-' . trim($row['city_code']));

                $stops[] = [
                    'name'    => trim($row['stop_name']),
                    'slug'    => $slug,
                    'city_id' => $cityId,
                    'code'    => 'S-' . ++$stopCode,
                ];
            }

            // Stop::insertOrIgnore($stops);

            Stop::upsert($stops, ['code']);
        });
    }
}
