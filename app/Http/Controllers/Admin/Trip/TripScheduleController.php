<?php
namespace App\Http\Controllers\Admin\Trip;

use App\Enums\AuthStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trip\TripStoreRequest;
use App\Models\TripSchedule;
use App\Services\Bus\TripService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;

class TripScheduleController extends Controller
{
    public function __construct(private TripService $tripService)
    {}

    public function index()
    {
        return view('backend.trip.index');
    }

    public function store(TripStoreRequest $request)
    {
        $request->merge(['auth_status' => AuthStatus::APPROVED->value]);

        $this->tripService->checkRouteAndCreate($request->all());

        return response()->json(['message' => 'Trip added successfully']);
    }

    public function update(TripStoreRequest $request, $trip)
    {
        $trip = $this->tripService->findOrfail($trip);

        $response = $this->tripService->checkRouteAndCreate($request->validated(), $trip->id);
        if (! $response['status']) {
            return response()->json(['status' => 'error', 'message' => $response['message']]);
        }

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
        $busId = $request->filter ?? null;

        $query = TripSchedule::select('id', 'bus_id', 'route_direction_id', 'departure_time', 'arrival_time', 'days_of_week', 'effective_from', 'effective_to', 'is_active', 'auth_status')
            ->with(['routeDirection', 'bus:id,bus_name,bus_number'])
            ->when($busId, fn($query) => $query->where('bus_id', $busId)->orderBy('departure_time'));

        return DataTables::of($query)
            ->addColumn('routeDirection', function ($row) {
                return $row->routeDirection ? "{$row->routeDirection->name}" : '-';
            })
            ->addColumn('departure_time', function ($row) {
                $from = Carbon::parse($row->departure_time)->format('g:i A');
                $to   = Carbon::parse($row->arrival_time)->format('g:i A');
                return "{$from} - {$to}";
            })
            ->addColumn('bus', function ($row) {
                $number = $row->bus ? '<span class="small text-muted">' . $row->bus->bus_number . '</span>' : '';

                return $row->bus ? "{$row->bus->bus_name} {$number}" : '-';
            })
            ->rawColumns(['routeDirection', 'bus'])
            ->make(true);
    }

    public function form($id, $attributeId = "")
    {
        $data = $id ? TripSchedule::with('bus:id,bus_name,bus_number', 'routeDirection')->findOrFail($id) : null;

        return view('backend.trip.form', compact('data'));
    }
}
