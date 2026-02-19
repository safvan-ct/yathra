<?php
namespace App\Http\Controllers\Operator;

use App\Enums\BusAuthStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bus\BusStoreRequest;
use App\Services\Bus\BusService;
use Illuminate\Support\Facades\Auth;

class BusController extends Controller
{
    public function __construct(private BusService $busService)
    {}

    public function index()
    {
        $buses = $this->busService->getOperatorBuseList(Auth::guard('operator')->user()->id);

        return view('operator.bus.index', compact('buses'));
    }

    public function store(BusStoreRequest $request)
    {
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
