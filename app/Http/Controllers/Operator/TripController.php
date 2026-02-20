<?php
namespace App\Http\Controllers\Operator;

use App\Enums\BusAuthStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bus\BusStoreRequest;
use App\Models\RouteDirection;
use App\Models\Stop;
use App\Models\TripSchedule;
use App\Services\Bus\BusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function __construct(private BusService $busService)
    {}

    public function index(Request $request)
    {
        $buses = $this->busService->getBuseList(Auth::guard('operator')->user()->id);

        $trips = [];
        $busId = $request->has('bus') ? $request->bus : (count($buses) == 1 ? $buses[0]->id : null);

        if ($busId) {
            $trips = TripSchedule::with(['routeDirection.routePattern', 'bus'])->where('bus_id', $busId)->orderBy('departure_time')->get();
        }

        return view('operator.trip.index', compact('buses', 'busId', 'trips'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bus'          => 'required|exists:buses,id',
            'from'         => 'required|exists:stops,id',
            'end'          => 'required|exists:stops,id',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i|after_or_equal:start_time',
            'day_of_weeks' => 'required|array',
        ]);

        $checkTripExist = TripSchedule::where('bus_id', $request->bus)
            ->where('origin_stop_id', $request->from)
            ->where('departure_time', $request->start_time)
            ->first();

        if ($checkTripExist) {
            return redirect()->back()->with('error', 'Trip already exist.');
        }

        $checkRouteDirectionExist = RouteDirection::where('origin_stop_id', $request->from)->where('destination_stop_id', $request->end)->first();

        if (! $checkRouteDirectionExist) {
            $origin = Stop::where('id', $request->from)->first();
            $dest   = Stop::where('id', $request->end)->first();

            RouteDirection::create([
                'name'                => $origin->from . ' - ' . $dest->end,
                'direction'           => 'up',
                'origin_stop_id'      => $request->from,
                'destination_stop_id' => $request->end,
            ]);
        }

        TripSchedule::create([
            'route_direction_id'  => $checkRouteDirectionExist->id,
            'bus_id'              => $request->bus,
            'origin_stop_id'      => $request->from,
            'destination_stop_id' => $request->end,
            'departure_time'      => $request->start_time,
            'arrival_time'        => $request->end_time,
            'day_of_weeks'        => $request->day_of_weeks,
        ]);

        dd($request->all());
        $this->busService->store([
            'operator_id' => Auth::guard('operator')->user()->id,
            'bus_name'    => $request->bus_name,
            'bus_number'  => $request->bus_number,
            'bus_color'   => $request->bus_color,
            'is_active'   => 1,
            'auth_status' => BusAuthStatus::PENDING,
        ]);

        return redirect()->route('operator.bus.index')->with('success', 'The new bus have been added to your list successfully.');
    }

    public function update(BusStoreRequest $request, $bus)
    {
        $bus = $this->busService->find($bus);

        if ($bus->operator_id != Auth::guard('operator')->user()->id) {
            return abort(403);
        }

        $this->busService->update($bus, ['bus_name' => $request->bus_name, 'bus_number' => $request->bus_number, 'bus_color' => $request->bus_color]);

        return redirect()->route('operator.bus.index')->with('success', "{$bus->bus_name} ({$bus->bus_number}) have been updated successfully.");
    }
}
