<?php
namespace App\Services\Bus;

use App\Models\Bus;
use Illuminate\Support\Facades\DB;

class BusService
{
    public function getOperatorBuseList($operatorId = null)
    {
        return Bus::select('id', 'bus_name', 'bus_number', 'bus_color', 'auth_status', 'is_active')
            ->when($operatorId, fn($query) => $query->where('operator_id', $operatorId))
            ->get();
    }

    public function find($id)
    {
        return Bus::findOrFail($id);
    }

    public function store($data)
    {
        return Bus::create($data);
    }

    public function update($bus, $data)
    {
        return $bus->update($data);
    }

    public function search($seach)
    {
        $results = Bus::query()
            ->where(function ($query) use ($seach) {
                $query->where('bus_name', 'LIKE', "%{$seach}%")->orWhere('bus_number', 'LIKE', "%{$seach}%");
            })
            ->select(['id', DB::raw("CONCAT(bus_name, ' (', bus_number, ')') as name"), 'bus_name', 'bus_number'])
            ->limit(20)
            ->get();

        return $results;
    }
}
