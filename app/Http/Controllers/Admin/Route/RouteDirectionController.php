<?php
namespace App\Http\Controllers\Admin\Route;

use App\Http\Controllers\Controller;
use App\Imports\RouteDirectionImport;
use App\Imports\RouteDirectionPreviewImport;
use App\Models\RouteDirection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class RouteDirectionController extends Controller
{
    public function index()
    {
        return view('backend.route-direction.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'route_pattern_id' => "required|exists:route_patterns,id",
            'name'             => "nullable|string|max:255",
            'direction'        => "required|in:up,down",
        ]);

        RouteDirection::create([
            'route_pattern_id' => $request->route_pattern_id,
            'name'             => $request->name ?? '',
            'direction'        => $request->direction,
        ]);

        return response()->json(['message' => 'Route direction added successfully']);
    }

    public function update(Request $request, RouteDirection $routeDirection)
    {
        $request->validate(['name' => "nullable|string|max:255", 'direction' => "required|in:up,down"]);

        $routeDirection->update(['name' => $request->name ?? '', 'direction' => $request->direction]);

        return response()->json(['message' => 'Route direction updated successfully']);
    }

    public function toggleStatus(Request $request, $id)
    {
        $column = $request->column ?? 'is_active';
        $item   = RouteDirection::findOrFail($id);

        $item->$column = ! $item->$column;
        $item->save();

        return response()->json(['message' => 'Updated successfully']);
    }

    public function dataTable(Request $request)
    {
        $query = RouteDirection::select(['id', 'route_pattern_id', 'name', 'direction', 'is_active'])
            ->with(['routePattern:id,name,code'])
            ->withCount('stops');

        return DataTables::of($query)
            ->addColumn('routePattern', function ($row) {
                return $row->routePattern ? $row->routePattern->name . " ({$row->routePattern->code})" : '-';
            })
            ->addColumn('stops', function ($row) {
                return $row->stops_count;
            })
            ->make(true);
    }

    public function form($id = null)
    {
        $data = $id ? RouteDirection::with('routePattern:id,name,code')->findOrFail($id) : null;

        return view('backend.route-direction.form', compact('data'));
    }

    public function importPreview(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);

        $file = fopen($request->file('file')->getRealPath(), 'r');

        $header = fgetcsv($file);

        $expected = ['route_pattern_code', 'name', 'direction'];

        if ($header !== $expected) {
            return back()->with('error', 'Invalid CSV header format.');
        }

        $import = new RouteDirectionPreviewImport();

        Excel::import($import, $request->file('file'));

        $id   = Str::uuid()->toString();
        $path = $request->file('file')->storeAs("imports/route-directions", $id . '.csv');

        $preview = $import->preview;

        session(["route_direction_import_{$id}" => $path]);

        return view('backend.route-direction.import-preview', compact('preview', 'id'));
    }

    public function importConfirm($id)
    {
        $path = session("route_direction_import_{$id}");

        if (! $path) {
            return redirect()->route('route-direction.index')->with('error', 'Invalid CSV file.');
        }

        DB::transaction(function () use ($path) {
            Excel::import(new RouteDirectionImport(), $path);
        });

        Storage::delete($path);

        session()->forget("route_direction_import_{$id}");

        return redirect()->route('route-direction.index')->with('success', "Route directions imported successfully.");
    }
}
