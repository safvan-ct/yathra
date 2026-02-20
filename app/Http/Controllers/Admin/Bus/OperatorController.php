<?php
namespace App\Http\Controllers\Admin\Bus;

use App\Enums\OperatorAuthStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bus\OperatorStoreRequest;
use App\Imports\OperatorImportPreview;
use App\Imports\OperatorsImport;
use App\Models\Operator;
use App\Services\Bus\OperatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class OperatorController extends Controller
{
    public function __construct(private OperatorService $operatorService)
    {}

    public function index()
    {
        return view('backend.operator.index');
    }

    public function store(OperatorStoreRequest $request)
    {
        $this->operatorService->store([
            'name'        => $request->name,
            'phone'       => $request->phone,
            'pin'         => $request->register_pin,
            'type'        => $request->type,
            'auth_status' => OperatorAuthStatus::APPROVED,
        ]);

        return response()->json(['message' => 'Operator added successfully']);
    }

    public function update(OperatorStoreRequest $request, $id)
    {
        $this->operatorService->update($id, [
            'name'        => $request->name,
            'phone'       => $request->phone,
            'type'        => $request->type,
            'auth_status' => $request->auth_status,
        ]);

        return response()->json(['message' => 'Operator updated successfully']);
    }

    public function toggleStatus(Request $request, Operator $operator)
    {
        $column = $request->column ?? 'is_active';

        $this->operatorService->update($operator->id, [$column => ! $operator->$column]);

        return response()->json(['message' => 'Updated successfully']);
    }

    public function dataTable(Request $request)
    {
        return DataTables::of($this->operatorService->dataTable())->make(true);
    }

    public function form($id, $attributeId = "")
    {
        $data = $id ? $this->operatorService->findOrFail($id) : null;

        return view('backend.operator.form', compact('data'));
    }

    // Import Operator
    public function importPreview(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);

        $file = fopen($request->file('file')->getRealPath(), 'r');

        $header = fgetcsv($file);

        $expected = ['name', 'phone', 'pin', 'type'];

        if ($header !== $expected) {
            return back()->with('error', 'Invalid CSV header format.');
        }

        $import = new OperatorImportPreview();

        Excel::import($import, $request->file('file'));

        $id   = Str::uuid()->toString();
        $path = $request->file('file')->storeAs("imports/operators", $id . '.csv');

        $preview = $import->preview;

        session(["operator-import-preview-{$id}" => $path]);

        return view('backend.operator.import-preview', compact('preview', 'id'));
    }

    public function importConfirm($id)
    {
        $path = session("operator-import-preview-{$id}");

        if (! $path) {
            return redirect()->route('bus-operator.index')->with('error', 'Invalid CSV file.');
        }

        DB::transaction(function () use ($path) {
            Excel::import(new OperatorsImport(), $path);
        });

        Storage::delete($path);

        session()->forget("operator-import-preview-{$id}");

        return redirect()->route('bus-operator.index')->with('success', "Operators imported successfully.");
    }

    public function search(Request $request)
    {
        $results = $this->operatorService->search($request->q);

        return response()->json($results);
    }
}
