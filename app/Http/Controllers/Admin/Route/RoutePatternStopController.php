<?php
namespace App\Http\Controllers\Admin\Route;

use App\Http\Controllers\Controller;
use App\Models\RoutePattern;
use App\Models\RoutePatternStop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoutePatternStopController extends Controller
{
    public function index(RoutePattern $routePattern)
    {
        $stops = RoutePatternStop::with('stop:id,name,code')
            ->where('route_pattern_id', $routePattern->id)
            ->orderBy('stop_order')
            ->get()
            ->map(function ($item) {
                return [
                    'id'     => $item->stop_id,
                    'name'   => $item->stop->name . ' (' . $item->stop->code . ')',
                    'offset' => $item->minutes_from_previous_stop ?? 0,
                ];
            })
            ->values(); // reset indexes

        $allPatterns = RoutePattern::select('id', 'name')->where('id', '!=', $routePattern->id)->get();

        return view('backend.route-pattern-stop.index', compact('routePattern', 'stops', 'allPatterns'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'route_pattern_id'   => 'required|exists:route_patterns,id',
            'stops'              => 'required|array|min:1',
            'stops.*.stop_id'    => 'required|exists:stops,id',
            'stops.*.stop_order' => 'required|integer|min:1',
            'stops.*.offset'     => 'required|integer|min:0',
        ]);

        $routeId = $request->route_pattern_id;
        $stops   = $request->stops;

        $ids = collect($stops)->pluck('stop_id');

        if ($ids->duplicates()->isNotEmpty()) {
            return back()->withErrors("Duplicate stops detected.");
        }

        $stops  = collect($stops)->sortBy('stop_order')->values();
        $offset = 0;

        DB::transaction(function () use ($routeId, $stops, $offset) {

            /* DELETE OLD STOPS */
            RoutePatternStop::where('route_pattern_id', $routeId)->delete();

            /* INSERT CLEAN DATA */
            foreach ($stops as $index => $stop) {
                $previous = $index + 1 == 1 ? 0 : ($stop['offset'] ?? 5);
                $offset   = $offset + $previous;

                RoutePatternStop::create([
                    'route_pattern_id'           => $routeId,
                    'stop_id'                    => $stop['stop_id'],
                    'stop_order'                 => $index + 1, // force sequential order
                    'minutes_from_previous_stop' => $previous,
                    'default_offset_minutes'     => $offset,
                ]);
            }
        });

        return redirect()
            ->route('backend.route-pattern-stop.index', $routeId)
            ->with('success', 'Route stops saved successfully.');

        dd($request->all());
        $request->validate([
            'name'                => "required|string|max:255",
            'info'                => "required|string|max:255",
            'origin_stop_id'      => "required|exists:stops,id",
            'destination_stop_id' => "required|exists:stops,id",
        ]);

        RoutePattern::create([
            'name'                => $request->name,
            'info'                => $request->info,
            'origin_stop_id'      => $request->origin_stop_id,
            'destination_stop_id' => $request->destination_stop_id,
        ]);

        return response()->json(['message' => 'Route pattern added successfully']);
    }
}
