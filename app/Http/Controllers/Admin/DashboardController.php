<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\RoutePattern;
use App\Models\RoutePatternStop;
use App\Models\Stop;
use App\Models\Trip;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuses  = Bus::count();
        $totalStops  = Stop::count();
        $totalRoutes = RoutePattern::count();
        $totalTrips  = Trip::count();

        return view('backend.dashboard', [
            'totalBuses'  => $totalBuses,
            'totalStops'  => $totalStops,
            'totalRoutes' => $totalRoutes,
            'totalTrips'  => $totalTrips,
        ]);
    }

    public function stops(Request $request)
    {
        $q = $request->q;

        $users = Stop::select('id', 'name', 'code', 'local_governing_body', 'legislative_assembly', 'district', 'state', 'pincode')
            ->where('name', 'LIKE', "%$q%")
            ->limit(20)
            ->get();

        return response()->json($users);
    }

    public function getPatternStops($id)
    {
        $stops = RoutePatternStop::with('stop')
            ->where('route_pattern_id', $id)
            ->orderBy('stop_order')
            ->get()
            ->map(function ($row) {
                return [
                    'id'     => $row->stop_id,
                    'name'   => $row->stop->name . " (" . $row->stop->code . ")",
                    'offset' => $row->minutes_from_previous_stop ?? 0,
                ];
            });

        return response()->json($stops);
    }
}
