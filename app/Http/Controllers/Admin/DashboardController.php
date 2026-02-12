<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\RoutePattern;
use App\Models\Stop;
use App\Models\Trip;

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
}
