<?php
namespace App\Http\Controllers\Admin\Stop;

use App\Http\Controllers\Controller;
use App\Imports\CitiesImport;
use App\Models\City;
use App\Models\District;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class CityController extends Controller
{
    public function index()
    {
        return view('backend.city.index');
    }

    public function store(Request $request)
    {
        $request->validate(['district_id' => 'required|exists:districts,id', 'name' => 'required|string|max:255']);

        City::create(['district_id' => $request->district_id, 'name' => $request->name]);

        return response()->json(['message' => 'City added successfully']);
    }

    public function update(Request $request, City $city)
    {
        $request->validate(['name' => 'required|string|max:255']);

        $city->update(['name' => $request->name]);

        return response()->json(['message' => 'City updated successfully']);
    }

    public function toggleStatus(Request $request, City $city)
    {
        $column = $request->column ?? 'is_active';

        $city->$column = ! $city->$column;
        $city->save();

        return response()->json(['message' => 'Updated successfully']);
    }

    public function dataTable(Request $request)
    {
        $query = City::select('id', 'district_id', 'name', 'is_active')->with('district:id,name');

        return DataTables::of($query)
            ->addColumn('district', function ($row) {
                return $row->district ? $row->district->name : '-';
            })
            ->make(true);
    }

    public function form($id, $attributeId = "")
    {
        $data = $id ? City::findOrFail($id) : null;

        return view('backend.city.form', compact('data'));
    }

    // Import Cities
    public function importConfirm(Request $request, District $district)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);

        $file = fopen($request->file('file')->getRealPath(), 'r');

        $header = fgetcsv($file);

        $expected = ['state_code', 'district_name', 'name'];

        if ($header !== $expected) {
            return back()->with('error', 'Invalid CSV header format.');
        }

        Excel::import(new CitiesImport, $request->file('file'));

        return redirect()->route('city.index')->with('success', "Cities imported successfully.");
    }

    public function search(Request $request)
    {
        $q = $request->q;

        $cities = City::select('id', 'name')
            ->where('name', 'LIKE', "%$q%")
            ->limit(20)
            ->get();

        return response()->json($cities);
    }
}
