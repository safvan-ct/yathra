<?php
namespace App\Imports;

use App\Enums\AuthStatus;
use App\Models\RouteDirection;
use App\Models\Stop;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RouteDirectionImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $origin = Stop::select('id', 'name')->where('code', $row['origin_code'])->first();
        $dest   = Stop::select('id', 'name')->where('code', $row['destination_code'])->first();

        if (! $origin || ! $dest) {
            return null;
        }

        $duplicate = RouteDirection::where('origin_stop_id', $origin->id)->where('destination_stop_id', $dest->id)->exists();

        if ($duplicate) {
            return null;
        }

        return RouteDirection::updateOrCreate(['origin_stop_id' => $origin->id, 'destination_stop_id' => $dest->id], [
            'name' => "{$origin->name} - {$dest->name}",
            'auth_status' => AuthStatus::APPROVED->value,
        ]);
    }
}
