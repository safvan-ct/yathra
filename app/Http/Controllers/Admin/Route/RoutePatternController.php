<?php
namespace App\Http\Controllers\Admin\Route;

use App\Http\Controllers\Controller;
use App\Models\RoutePattern;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RoutePatternController extends Controller
{
    public function index()
    {
        return view('backend.route-pattern.index');
    }

    public function dataTable(Request $request)
    {
        $query = RoutePattern::select('id', 'name', 'info', 'origin_stop_id', 'destination_stop_id', 'is_active')->with(['origin', 'destination']);

        return DataTables::of($query)
            ->addColumn('origin_stop', function ($row) {
                return $row->origin ? $row->origin->name . ' (' . $row->origin->code . ')' : '-';
            })
            ->addColumn('destination_stop', function ($row) {
                return $row->destination ? $row->destination->name . ' (' . $row->destination->code . ')' : '-';
            })
            ->make(true);
    }

    public function form($id = null)
    {
        $data = $id ? RoutePattern::with('origin', 'destination')->findOrFail($id) : null;

        return view('backend.route-pattern.form', compact('data'));
    }

    public function store(Request $request)
    {
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'                => "required|string|max:255",
            'info'                => "required|string|max:255",
            'origin_stop_id'      => "required|exists:stops,id",
            'destination_stop_id' => "required|exists:stops,id",
        ]);

        RoutePattern::findOrFail($id)->update([
            'name'                => $request->name,
            'info'                => $request->info,
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
}
