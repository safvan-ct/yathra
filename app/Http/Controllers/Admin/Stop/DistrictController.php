<?php
namespace App\Http\Controllers\Admin\Stop;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DistrictController extends Controller
{
    public function index()
    {
        return view('backend.district.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
        ]);

        District::create([
            'state_id' => 1,
            'name'     => $request->name,
            //'code'     => $request->code,
        ]);

        return response()->json(['message' => 'District added successfully']);
    }

    public function update(Request $request, District $district)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
        ]);

        $district->update([
            'name' => $request->name,
            //'code' => $request->code,
        ]);

        return response()->json(['message' => 'District updated successfully']);
    }

    public function toggleStatus(Request $request, District $district)
    {
        $column = $request->column ?? 'is_active';

        $district->$column = ! $district->$column;
        $district->save();

        return response()->json(['message' => 'Updated successfully']);
    }

    public function dataTable(Request $request)
    {
        return DataTables::of(District::select('id', 'name', 'code', 'is_active'))->make(true);
    }

    public function form($id, $attributeId = "")
    {
        $data = $id ? District::findOrFail($id) : null;

        return view('backend.district.form', compact('data'));
    }

    /**
     * Import Districts
     *
     */
    public function importPreview(Request $request, State $state)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);

        $file = fopen($request->file('file')->getRealPath(), 'r');

        $header = fgetcsv($file);

        $expected = ['id', 'code', 'name', 'headquarters'];

        if ($header !== $expected) {
            return back()->with('error', 'Invalid CSV header format.');
        }

        $preview   = [];
        $errors    = [];
        $rowNumber = 1;

        while (($row = fgetcsv($file)) !== false) {

            $rowNumber++;

            $data = array_combine($header, $row);

            $validator = Validator::make($data, ['name' => 'required|string|max:255', 'code' => 'required|string|max:50']);

            $exists = District::where('state_id', $state->id)->where('name', $data['name'])->exists();

            $preview[] = [
                'data'      => $data,
                'valid'     => ! $validator->fails() && ! $exists,
                'errors'    => $validator->errors()->all(),
                'duplicate' => $exists,
            ];
        }

        fclose($file);

        Session::put("district_import_preview_{$state->id}", $preview);

        return view('backend.district.import-preview', compact('preview', 'state'));
    }

    public function importConfirm(State $state)
    {
        $preview = Session::get("district_import_preview_{$state->id}");

        if (! $preview) {
            return redirect()->route('district.index')->with('error', 'No import session found.');
        }

        $insertData = [];

        foreach ($preview as $row) {

            if (! $row['valid']) {
                continue;
            }

            $data = $row['data'];

            $insertData[] = [
                'state_id'   => $state->id,
                'name'       => $data['name'],
                'code'       => $data['code'],
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        try {
            DB::transaction(function () use ($insertData) {
                District::insert($insertData);
            });
        } catch (\Throwable $e) {
            return redirect()->route('district.index')->with('error', 'Import failed: ' . $e->getMessage());
        }

        Session::forget("district_import_preview_{$state->id}");

        return redirect()->route('district.index')->with('success', "Districts of {$state->name} imported successfully.");
    }

    public function search(Request $request)
    {
        $q = $request->q;

        $districts = District::select('id', 'name')
            ->where('name', 'LIKE', "%$q%")
            ->limit(20)
            ->get();

        return response()->json($districts);
    }
}
