<?php
namespace App\Http\Controllers\Admin\Route;

use App\Http\Controllers\Controller;
use App\Models\Stop;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StopController extends Controller
{
    public function index()
    {
        return view('backend.stop.index');
    }

    public function dataTable(Request $request)
    {
        return DataTables::of(Stop::select('id', 'name', 'code', 'is_bus_terminal', 'is_active'))->make(true);
    }

    public function form($id = null)
    {
        $data = $id ? Stop::findOrFail($id) : null;

        return view('backend.stop.form', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                 => "required|string|max:255",
            'code'                 => "required|string|max:255|unique:stops,code",

            'local_governing_body' => "nullable|string|max:255",
            'legislative_assembly' => "nullable|string|max:255",
            'district'             => "nullable|string|max:255",
            'state'                => "nullable|string|max:255",
            'pincode'              => "nullable|integer",

            'latitude'             => "nullable|numeric",
            'longitude'            => "nullable|numeric",
        ]);

        Stop::create([
            'name'                 => $request->name,
            'code'                 => $request->code,
            'local_governing_body' => $request->local_governing_body,
            'legislative_assembly' => $request->legislative_assembly,
            'district'             => $request->district,
            'state'                => $request->state,
            'pincode'              => $request->pincode,
            'latitude'             => $request->latitude,
            'longitude'            => $request->longitude,
        ]);

        return response()->json(['message' => 'Stop added successfully']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'                 => "required|string|max:255",
            'code'                 => "required|string|max:255|unique:stops,code,$id",

            'local_governing_body' => "nullable|string|max:255",
            'legislative_assembly' => "nullable|string|max:255",
            'district'             => "nullable|string|max:255",
            'state'                => "nullable|string|max:255",
            'pincode'              => "nullable|integer",

            'latitude'             => "nullable|numeric",
            'longitude'            => "nullable|numeric",
        ]);

        Stop::findOrFail($id)->update([
            'name'                 => $request->name,
            'code'                 => $request->code,
            'local_governing_body' => $request->local_governing_body,
            'legislative_assembly' => $request->legislative_assembly,
            'district'             => $request->district,
            'state'                => $request->state,
            'pincode'              => $request->pincode,
            'latitude'             => $request->latitude,
            'longitude'            => $request->longitude,
        ]);

        return response()->json(['message' => 'Stop updated successfully']);
    }

    public function toggleStatus(Request $request, $id)
    {
        $column = $request->column ?? 'is_active';
        $item   = Stop::findOrFail($id);

        $item->$column = ! $item->$column;
        $item->save();

        return response()->json(['message' => 'Updated successfully']);
    }
}
