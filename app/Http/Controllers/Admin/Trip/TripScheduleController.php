<?php
namespace App\Http\Controllers\Admin\Trip;

use App\Http\Controllers\Controller;
use App\Models\TripSchedule;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TripScheduleController extends Controller
{
    public function index()
    {
        return view('backend.trip.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_direction_id' => 'required|exists:route_directions,id',
            'bus_id'             => 'required|exists:buses,id',
            'departure_time'     => 'required',
            'days_of_week'       => 'required|array',
            'effective_from'     => 'required|date',
            'effective_to'       => 'nullable|date|after_or_equal:effective_from',
        ]);

        TripSchedule::create($validated);

        return response()->json(['message' => 'Trip added successfully']);
    }

    public function update(Request $request, $trip)
    {
        $trip = TripSchedule::findOrFail($trip);

        $validated = $request->validate([
            'route_direction_id' => 'required|exists:route_directions,id',
            'bus_id'             => 'required|exists:buses,id',
            'departure_time'     => 'required',
            'days_of_week'       => 'required|array',
            'effective_from'     => 'required|date',
            'effective_to'       => 'nullable|date|after_or_equal:effective_from',
        ]);

        $trip->update($validated);

        return response()->json(['message' => 'Trip updated successfully']);
    }

    public function toggleStatus(Request $request, TripSchedule $tripSchedule)
    {
        $column = $request->column ?? 'is_active';

        $tripSchedule->$column = ! $tripSchedule->$column;
        $tripSchedule->save();

        return response()->json(['message' => 'Updated successfully']);
    }

    public function dataTable(Request $request)
    {
        $query = TripSchedule::select('id', 'bus_id', 'route_direction_id', 'departure_time', 'days_of_week', 'effective_from', 'effective_to', 'is_active')
            ->with(['routeDirection.routePattern', 'bus:id,bus_name,bus_number']);

        return DataTables::of($query)
            ->addColumn('routeDirection', function ($row) {
                return $row->routeDirection ? "{$row->routeDirection->routePattern->name} ({$row->routeDirection->routePattern->code}) {$row->routeDirection->name} {$row->routeDirection->direction}" : '-';
            })
            ->addColumn('bus', function ($row) {
                return $row->bus ? "{$row->bus->bus_name} ({$row->bus->bus_number})" : '-';
            })
            ->make(true);
    }

    public function form($id, $attributeId = "")
    {
        $data = $id ? TripSchedule::with('bus:id,bus_name,bus_number', 'routeDirection.routePattern')->findOrFail($id) : null;

        return view('backend.trip.form', compact('data'));
    }
}
