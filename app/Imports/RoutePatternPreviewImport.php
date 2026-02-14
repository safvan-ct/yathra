<?php
namespace App\Imports;

use App\Models\RoutePattern;
use App\Models\Stop;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RoutePatternPreviewImport implements ToCollection, WithHeadingRow
{
    public array $preview = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            $origin      = Stop::select('id', 'name', 'code')->where('code', $row['origin_code'])->first();
            $destination = Stop::select('id', 'name', 'code')->where('code', $row['destination_code'])->first();

            $valid   = true;
            $message = '';

            if (! $origin || ! $destination) {
                $message .= 'Origin or destination not found,';
                $valid    = false;
            }

            if ($origin->id === $destination->id) {
                $message .= 'Origin and destination cannot be same, ';
                $valid    = false;
            }

            $duplicate = RoutePattern::where('origin_stop_id', $origin->id)
                ->where('destination_stop_id', $destination->id)
                ->exists();

            if ($duplicate) {
                $message .= 'Duplicate route found,';
                $valid    = false;
            }

            $this->preview[] = [
                'name'   => $row['name'],
                'origin' => "{$origin->name} ({$origin->code})",
                'destination' => "{$destination->name} ({$destination->code})",
                'valid'   => $valid,
                'message' => $message,
            ];
        }
    }
}
