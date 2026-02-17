<?php
namespace App\Http\Controllers;

use App\Models\Stop;
use App\Models\TripSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $fromStopId = $request->filled('from') ? Stop::where('code', $request->from)->first()->id : null;
        $toStopId   = $request->filled('to') ? Stop::where('code', $request->to)->first()->id : null;

        $buses = DB::table('trip_schedules as ts')

        // Join bus + operator
            ->join('buses as b', 'b.id', '=', 'ts.bus_id')

        // FROM stop
            ->join('route_direction_stops as rds_from', function ($join) use ($fromStopId) {
                $join->on('rds_from.route_direction_id', '=', 'ts.route_direction_id')
                    ->where('rds_from.stop_id', '=', $fromStopId);
            })

        // TO stop
            ->join('route_direction_stops as rds_to', function ($join) use ($toStopId) {
                $join->on('rds_to.route_direction_id', '=', 'ts.route_direction_id')
                    ->where('rds_to.stop_id', '=', $toStopId);
            })

        // Ensure correct travel direction
            ->whereColumn('rds_from.stop_order', '<', 'rds_to.stop_order')

        // Active schedule only
            ->where('ts.is_active', true)

            ->select([
                'ts.id as trip_id',
                'b.bus_name',
                'b.bus_number',
                'ts.departure_time',

                DB::raw("ADDTIME(ts.departure_time, SEC_TO_TIME(rds_from.default_offset_minutes * 60) ) as departure_time"),

                DB::raw("ADDTIME(ts.departure_time, SEC_TO_TIME(rds_to.default_offset_minutes * 60)) as arrival_time"),
            ])

            ->orderBy('departure_time')
            ->get()
            ->toArray();

        $fromStop = request('from')
            ? Stop::select('id', 'city_id', 'name', 'code')->with('city.district')->where('code', request('from'))->first()
            : null;

        $toStop = request('to')
            ? Stop::select('id', 'city_id', 'name', 'code')->with('city.district')->where('code', request('to'))->first()
            : null;

        return view('home', compact('buses', 'fromStop', 'toStop'));
    }

    public function showTrip($tripId, $from, $to)
    {
        $trip = TripSchedule::findOrFail($tripId);

        $fromStopId = Stop::where('code', $from)->first()->id;
        $toStopId   = Stop::where('code', $to)->first()->id;

        $stops = DB::table('route_direction_stops as rds')
            ->join('stops as s', 's.id', '=', 'rds.stop_id')

            ->where('rds.route_direction_id', $trip->route_direction_id)
            ->orderBy('rds.stop_order')

            ->select([
                's.id as stop_id',
                's.name',
                's.code',
                'rds.stop_order',
                'rds.default_offset_minutes as offset',
            ])
            ->get();

        $fromOrder = $stops->firstWhere('stop_id', $fromStopId)?->stop_order;
        $toOrder   = $stops->firstWhere('stop_id', $toStopId)?->stop_order;

        $stops = $stops->map(function ($stop) use ($trip, $fromOrder, $toOrder, $fromStopId, $toStopId) {

            $startTime = \Carbon\Carbon::parse($trip->departure_time);

            $arrivalTime   = $startTime->copy()->addMinutes($stop->offset);
            $departureTime = $arrivalTime->copy()->addMinutes(1); // 1 min halt

            $stop->trip_start_time = $startTime->format('h:i A');
            $stop->arrival_time    = $arrivalTime->format('h:i A');
            $stop->departure_time  = $departureTime->format('h:i A');

            // Highlight selected segment
            $stop->is_in_between = (
                $fromOrder !== null &&
                $toOrder !== null &&
                $stop->stop_order >= $fromOrder &&
                $stop->stop_order <= $toOrder
            );

            $stop->is_selected_segment = in_array($stop->stop_id, [$fromStopId, $toStopId]);

            return $stop;
        });

        return view('bus-route', compact('trip', 'stops', 'from', 'to'));
    }

}
