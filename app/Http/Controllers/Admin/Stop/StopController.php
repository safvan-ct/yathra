<?php
namespace App\Http\Controllers\Admin\Stop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StopCreateRequest;
use App\Imports\StopsImport;
use App\Models\City;
use App\Models\District;
use App\Models\Stop;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class StopController extends Controller
{
    public function index()
    {
        return view('backend.stop.index');
    }

    public function store(StopCreateRequest $request)
    {
        $city = City::findOrFail($request->city_id);

        $slug = Str::slug($request->name . '-' . $city->name);

        Stop::create([
            'city_id'   => $request->city_id,
            'name'      => $request->name,
            'code'      => $request->code,
            'slug'      => $slug,
            'locality'  => $request->locality,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json(['message' => 'Stop added successfully']);
    }

    public function update(StopCreateRequest $request, Stop $stop)
    {
        $stop->update([
            'name'      => $request->name,
            'locality'  => $request->locality,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json(['message' => 'Stop updated successfully']);
    }

    public function toggleStatus(Request $request, Stop $stop)
    {
        $column = $request->column ?? 'is_active';

        $stop->$column = ! $stop->$column;
        $stop->save();

        return response()->json(['message' => 'Updated successfully']);
    }

    public function dataTable(Request $request)
    {
        $query = Stop::select('id', 'city_id', 'name', 'code', 'locality', 'is_active')->with('city:id,name,code');

        return DataTables::of($query)
            ->addColumn('city', function ($row) {
                return $row->city ? $row->city->name . " ({$row->city->code})" : '-';
            })
            ->make(true);
    }

    public function form($id, $attributeId = "")
    {
        $data = $id ? Stop::findOrFail($id) : null;

        return view('backend.stop.form', compact('data'));
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

        $results = Stop::select('id', 'name', 'code', 'city_id', 'locality')
            ->with('city:id,name')
            ->where('name', 'LIKE', "%$q%")
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
