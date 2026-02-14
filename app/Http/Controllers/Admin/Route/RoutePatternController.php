<?php
namespace App\Http\Controllers\Admin\Route;

use App\Http\Controllers\Controller;
use App\Imports\RoutePatternImport;
use App\Imports\RoutePatternPreviewImport;
use App\Models\RoutePattern;
use App\Models\Stop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class RoutePatternController extends Controller
{
    public function index()
    {
        return view('backend.route-pattern.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                => "required|string|max:255",
            'origin_stop_id'      => "required|exists:stops,id",
            'destination_stop_id' => "required|exists:stops,id",
        ]);

        $route = RoutePattern::create([
            'name'                => $request->name,
            'origin_stop_id'      => $request->origin_stop_id,
            'destination_stop_id' => $request->destination_stop_id,
        ]);

        $origin = Stop::select('id', 'name', 'code')->where('id', $request->origin_stop_id)->first();
        $dest   = Stop::select('id', 'name', 'code')->where('id', $request->destination_stop_id)->first();

        $route->code = $origin->code . '-' . $dest->code;
        $route->save();

        return response()->json(['message' => 'Route added successfully']);
    }

    public function update(Request $request, RoutePattern $routePattern)
    {
        $request->validate([
            'name'                => "required|string|max:255",
            'origin_stop_id'      => "required|exists:stops,id",
            'destination_stop_id' => "required|exists:stops,id",
        ]);

        $routePattern->update([
            'name'                => $request->name,
            'origin_stop_id'      => $request->origin_stop_id,
            'destination_stop_id' => $request->destination_stop_id,
        ]);

        return response()->json(['message' => 'Route pattern updated successfully']);
    }

    public function toggleStatus(Request $request, $id)
    {
        $column = $request->column ?? 'is_active';
        $item   = RoutePattern::findOrFail($id);

        $item->$column = ! $item->$column;
        $item->save();

        return response()->json(['message' => 'Updated successfully']);
    }

    public function dataTable(Request $request)
    {
        $query = RoutePattern::select('id', 'name', 'code', 'origin_stop_id', 'destination_stop_id', 'distance_km', 'is_active', )
            ->with(['origin:id,name,code', 'destination:id,name,code']);

        return DataTables::of($query)
            ->addColumn('origin', function ($row) {
                return $row->origin ? $row->origin->name . " ({$row->origin->code})" : '-';
            })
            ->addColumn('destination', function ($row) {
                return $row->destination ? $row->destination->name . " ({$row->destination->code})" : '-';
            })
            ->make(true);
    }

    public function form($id = null)
    {
        $data = $id ? RoutePattern::with('origin', 'destination')->findOrFail($id) : null;

        return view('backend.route-pattern.form', compact('data'));
    }

    public function search(Request $request)
    {
        $q = $request->q;

        $results = RoutePattern::select('id', 'name', 'code')
            ->where('name', 'LIKE', "%$q%")
            ->limit(20)
            ->get();

        return response()->json($results);
    }

    public function importPreview(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);

        $file = fopen($request->file('file')->getRealPath(), 'r');

        $header = fgetcsv($file);

        $expected = ['name', 'origin_code', 'destination_code'];

        if ($header !== $expected) {
            return back()->with('error', 'Invalid CSV header format.');
        }

        $import = new RoutePatternPreviewImport();

        Excel::import($import, $request->file('file'));

        $id   = Str::uuid()->toString();
        $path = $request->file('file')->storeAs("imports/route-patterns", $id . '.csv');

        $preview = $import->preview;

        session(["route_pattern_import_{$id}" => $path]);

        return view('backend.route-pattern.import-preview', compact('preview', 'id'));
    }

    public function importConfirm($id)
    {
        $path = session("route_pattern_import_{$id}");

        if (! $path) {
            return redirect()->route('route-pattern.index')->with('error', 'Invalid CSV file.');
        }

        DB::transaction(function () use ($path) {
            Excel::import(new RoutePatternImport(), $path);
        });

        Storage::delete($path);

        session()->forget("route_pattern_import_{$id}");

        return redirect()->route('route-pattern.index')->with('success', "Routes imported successfully.");
    }
}
