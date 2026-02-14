<?php
namespace App\Http\Controllers\Admin\Route;

use App\Http\Controllers\Controller;
use App\Imports\RouteDirectionStopImport;
use App\Imports\RouteDirectionStopPreviewImport;
use App\Models\RouteDirection;
use App\Models\RouteDirectionStop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class RouteDirectionStopController extends Controller
{
    public function index(RouteDirection $routeDirection)
    {
        $stops = RouteDirectionStop::with('stop:id,name,code')
            ->where('route_direction_id', $routeDirection->id)
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

        $allDirections = RouteDirection::select('id', 'route_pattern_id', 'name', 'direction')
            ->with(['routePattern:id,name,code'])
            ->where('id', '!=', $routeDirection->id)
            ->get();

        return view('backend.route-direction-stop.index', compact('routeDirection', 'stops', 'allDirections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'route_direction_id' => 'required|exists:route_directions,id',
            'stops'              => 'required|array|min:1',
            'stops.*.stop_id'    => 'required|exists:stops,id',
            'stops.*.stop_order' => 'required|integer|min:1',
            'stops.*.offset'     => 'required|integer|min:0',
        ]);

        $routeId = $request->route_direction_id;
        $stops   = $request->stops;

        $ids = collect($stops)->pluck('stop_id');

        if ($ids->duplicates()->isNotEmpty()) {
            return back()->withErrors("Duplicate stops detected.");
        }

        $stops  = collect($stops)->sortBy('stop_order')->values();
        $offset = 0;

        DB::transaction(function () use ($routeId, $stops, $offset) {

            /* DELETE OLD STOPS */
            RouteDirectionStop::where('route_direction_id', $routeId)->delete();

            /* INSERT CLEAN DATA */
            foreach ($stops as $index => $stop) {
                $previous = $index + 1 == 1 ? 0 : ($stop['offset'] ?? 5);
                $offset   = $offset + $previous;

                RouteDirectionStop::create([
                    'route_direction_id'         => $routeId,
                    'stop_id'                    => $stop['stop_id'],
                    'stop_order'                 => $index + 1, // force sequential order
                    'minutes_from_previous_stop' => $previous,
                    'default_offset_minutes'     => $offset,
                ]);
            }
        });

        return redirect()
            ->route('route-direction-stop.index', $routeId)
            ->with('success', 'Route stops saved successfully.');
    }

    public function importPreview(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);

        $file = fopen($request->file('file')->getRealPath(), 'r');

        $header = fgetcsv($file);

        $expected = ['route_direction_id', 'stop_code', 'stop_order', 'minutes_from_previous_stop'];

        if ($header !== $expected) {
            return back()->with('error', 'Invalid CSV header format.');
        }

        $import = new RouteDirectionStopPreviewImport();

        Excel::import($import, $request->file('file'));

        $id   = Str::uuid()->toString();
        $path = $request->file('file')->storeAs("imports/route-direction-stops", $id . '.csv');

        $preview = $import->preview;

        session(["route_direction_stop_import_{$id}" => $path]);

        return view('backend.route-direction-stop.import-preview', compact('preview', 'id'));
    }

    public function importConfirm($id)
    {
        $path = session("route_direction_stop_import_{$id}");

        if (! $path) {
            return redirect()->route('route-direction.index')->with('error', 'Invalid CSV file.');
        }

        DB::transaction(function () use ($path) {
            Excel::import(new RouteDirectionStopImport(), $path);
        });

        Storage::delete($path);

        session()->forget("route_direction_stop_import_{$id}");

        return redirect()->route('route-direction.index')->with('success', "Route direction stops imported successfully.");
    }

    public function getStops($id)
    {
        $stops = RouteDirectionStop::with('stop')
            ->where('route_direction_id', $id)
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
