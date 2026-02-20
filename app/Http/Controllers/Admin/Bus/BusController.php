<?php
namespace App\Http\Controllers\Admin\Bus;

use App\Enums\BusAuthStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bus\BusStoreRequest;
use App\Imports\BusImport;
use App\Imports\BusImportPreview;
use App\Models\Bus;
use App\Services\Bus\BusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class BusController extends Controller
{
    public function __construct(private BusService $busService)
    {}

    public function index()
    {
        return view('backend.bus.index');
    }

    public function store(BusStoreRequest $request)
    {
        $this->busService->store([
            'operator_id' => $request->operator_id,
            'bus_name'    => $request->bus_name,
            'bus_number'  => $request->bus_number,
            'slug'        => $request->slug,
            'bus_color'   => $request->bus_color,
            'auth_status' => BusAuthStatus::APPROVED,
        ]);

        return response()->json(['message' => 'Bus added successfully']);
    }

    public function update(BusStoreRequest $request, $bus)
    {
        $bus = $this->busService->find($bus);

        $this->busService->update($bus, [
            'operator_id' => $request->operator_id,
            'bus_name'    => $request->bus_name,
            'bus_number'  => $request->bus_number,
            'slug'        => $request->slug,
            'bus_color'   => $request->bus_color,
            'auth_status' => $request->auth_status,
        ]);

        return response()->json(['message' => 'Bus updated successfully']);
    }

    public function toggleStatus(Request $request, Bus $bus)
    {
        $column = $request->column ?? 'is_active';

        $this->busService->update($bus, [$column => ! $bus->$column]);

        return response()->json(['message' => 'Updated successfully']);
    }

    public function dataTable(Request $request)
    {
        $query = $this->busService->dataTable();

        return DataTables::of($query)->addColumn('operator', fn($row) => $row->operator ? $row->operator->name : '-')->make(true);
    }

    public function form($id, $attributeId = "")
    {
        $data = $id ? $this->busService->find($id) : null;

        return view('backend.bus.form', compact('data'));
    }

    // Import
    public function importPreview(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);

        $file = fopen($request->file('file')->getRealPath(), 'r');

        $header = fgetcsv($file);

        $expected = ['operator_id', 'bus_number', 'bus_name', 'bus_color'];

        if ($header !== $expected) {
            return back()->with('error', 'Invalid CSV header format.');
        }

        $import = new BusImportPreview();

        Excel::import($import, $request->file('file'));

        $id   = Str::uuid()->toString();
        $path = $request->file('file')->storeAs("imports/buses", $id . '.csv');

        $preview = $import->preview;

        session(["bus-import-preview-{$id}" => $path]);

        return view('backend.bus.import-preview', compact('preview', 'id'));
    }

    public function importConfirm($id)
    {
        $path = session("bus-import-preview-{$id}");

        if (! $path) {
            return redirect()->route('bus.index')->with('error', 'Invalid CSV file.');
        }

        DB::transaction(function () use ($path) {
            Excel::import(new BusImport(), $path);
        });

        Storage::delete($path);

        session()->forget("bus-import-preview-{$id}");

        return redirect()->route('bus.index')->with('success', "Buses imported successfully.");
    }

    public function search(Request $request)
    {
        $results = $this->busService->search($request->q);

        return response()->json($results);
    }
}
