<?php
namespace App\Http\Controllers\Operator;

use App\Enums\AuthStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trip\TripStoreRequest;
use App\Services\Bus\BusService;
use App\Services\Bus\TripService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function __construct(
        private BusService $busService,
        private TripService $tripService
    ) {}

    public function index(Request $request)
    {
        $buses = $this->busService->getBuseList(Auth::guard('operator')->user()->id);

        $trips = [];
        $busId = $request->has('bus') ? $request->bus : (count($buses) == 1 ? $buses[0]->id : null);

        if ($busId) {
            $trips = $this->tripService->getTripsByBus($busId);
        }

        return view('operator.trip.index', compact('buses', 'busId', 'trips'));
    }

    public function store(TripStoreRequest $request)
    {
        $request->merge(['auth_status' => AuthStatus::PENDING->value]);

        $this->tripService->checkRouteAndCreate($request->all());

        return redirect()->route('operator.trip.index')->with('success', 'The new trip have been added to your list successfully.');
    }

    public function update(TripStoreRequest $request, $trip)
    {
        $res = $this->tripService->findOrfail($trip);

        $response = $this->tripService->checkRouteAndCreate($request->validated(), $trip);
        if (! $response['status']) {
            return redirect()->route('operator.trip.index', ['bus' => $res->bus_id])->with('error', $response['message']);
        }

        return redirect()->route('operator.trip.index', ['bus' => $res->bus_id])->with('success', "Trip have been updated successfully.");
    }
}
