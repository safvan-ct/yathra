<?php
namespace App\Http\Controllers\Admin\Bus;

use App\Enums\OperatorType;
use App\Http\Controllers\Controller;
use App\Imports\StopsImport;
use App\Models\District;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class OperatorController extends Controller
{
    public function index()
    {
        return view('backend.operator.index');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'type' => ['required', new Enum(OperatorType::class)]]);

        Operator::create(['name' => $request->name, 'type' => $request->type]);

        return response()->json(['message' => 'Operator added successfully']);
    }

    public function update(Request $request, Operator $operator)
    {
        $request->validate(['name' => 'required', 'type' => ['required', new Enum(OperatorType::class)]]);

        $operator->update(['name' => $request->name, 'type' => $request->type]);

        return response()->json(['message' => 'Operator updated successfully']);
    }

    public function toggleStatus(Request $request, Operator $operator)
    {
        $column = $request->column ?? 'is_active';

        $operator->$column = ! $operator->$column;
        $operator->save();

        return response()->json(['message' => 'Updated successfully']);
    }

    public function dataTable(Request $request)
    {
        $query = Operator::select('id', 'name', 'type');

        return DataTables::of($query)->make(true);
    }

    public function form($id, $attributeId = "")
    {
        $data = $id ? Operator::findOrFail($id) : null;

        return view('backend.operator.form', compact('data'));
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

        $results = Operator::select('id', 'name')
            ->where('name', 'LIKE', "%$q%")
            ->limit(20)
            ->get();

        return response()->json($results);
    }
}
