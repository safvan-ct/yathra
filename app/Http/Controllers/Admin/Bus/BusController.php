<?php
namespace App\Http\Controllers\Admin\Bus;

use App\Http\Controllers\Controller;
use App\Imports\StopsImport;
use App\Models\Bus;
use App\Models\District;
use App\Models\Stop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class BusController extends Controller
{
    public function index()
    {
        return view('backend.bus.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'operator_id' => 'required|exists:operators,id',
            'bus_name'    => 'required',
            'bus_number'  => 'required|unique:buses,bus_number',
        ]);

        Bus::create([
            'operator_id' => $request->operator_id,
            'bus_name'    => $request->bus_name,
            'bus_number'  => $request->bus_number,
        ]);

        return response()->json(['message' => 'Bus added successfully']);
    }

    public function update(Request $request, $bus)
    {
        $bus = Bus::findOrFail($bus);

        $request->validate([
            'operator_id' => 'required|exists:operators,id',
            'bus_name'    => 'required',
            'bus_number'  => ['required', Rule::unique('buses', 'bus_number')->ignore($bus?->id)],
        ]);

        $bus->update([
            'operator_id' => $request->operator_id,
            'bus_name'    => $request->bus_name,
            'bus_number'  => $request->bus_number,
        ]);

        return response()->json(['message' => 'Bus updated successfully']);
    }

    public function toggleStatus(Request $request, Bus $bus)
    {
        $column = $request->column ?? 'is_active';

        $bus->$column = ! $bus->$column;
        $bus->save();

        return response()->json(['message' => 'Updated successfully']);
    }

    public function dataTable(Request $request)
    {
        $query = Bus::select('id', 'bus_name', 'bus_number', 'operator_id')->with('operator:id,name');

        return DataTables::of($query)
            ->addColumn('operator', function ($row) {
                return $row->operator ? $row->operator->name : '-';
            })
            ->make(true);
    }

    public function form($id, $attributeId = "")
    {
        $data = $id ? Bus::findOrFail($id) : null;

        return view('backend.bus.form', compact('data'));
    }

    // Import Stops
    public function importConfirm(Request $request, District $district)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);

        $file = fopen($request->file('file')->getRealPath(), 'r');

        $header = fgetcsv($file);

        $expected = ['state_code', 'district_name', 'city_name', 'stop_code', 'stop_name'];

        if ($header !== $expected) {
            return back()->with('error', 'Invalid CSV header format.');
        }

        Excel::import(new StopsImport, $request->file('file'));

        return redirect()->route('stop.index')->with('success', "Stops imported successfully.");
    }

    public function search(Request $request)
    {
        $q = $request->q;

        $results = Bus::query()
            ->where(function ($query) use ($q) {
                $query->where('bus_name', 'LIKE', "%{$q}%")->orWhere('bus_number', 'LIKE', "%{$q}%");
            })
            ->select(['id', DB::raw("CONCAT(bus_name, ' (', bus_number, ')') as name")])
            ->limit(20)
            ->get();

        return response()->json($results);
    }

    public function nearby($lat, $lng)
    {
        return Stop::selectRaw("
            *, (
                6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                )
            ) AS distance
        ", [$lat, $lng, $lat])
            ->having('distance', '<', 0.5)
            ->orderBy('distance')
            ->get();
    }
}
