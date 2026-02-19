<?php
namespace App\Http\Controllers\Admin\Bus;

use App\Enums\BusAuthStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bus\BusStoreRequest;
use App\Imports\StopsImport;
use App\Models\Bus;
use App\Models\District;
use App\Services\Bus\BusService;
use Illuminate\Http\Request;
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
            'bus_color'   => $request->bus_color,
            'auth_status' => BusAuthStatus::APPROVED,
        ]);

        return response()->json(['message' => 'Bus added successfully']);
    }

    public function update(Request $request, $bus)
    {
        $bus = $this->busService->find($bus);

        $this->busService->update($bus, [
            'operator_id' => $request->operator_id, 'bus_name' => $request->bus_name,
            'bus_number'  => $request->bus_number, 'bus_color' => $request->bus_color,
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
        $query = Bus::select('id', 'bus_name', 'bus_number', 'operator_id', 'is_active')->with('operator:id,name');

        return DataTables::of($query)
            ->addColumn('operator', function ($row) {
                return $row->operator ? $row->operator->name : '-';
            })
            ->make(true);
    }

    public function form($id, $attributeId = "")
    {
        $data = $id ? $this->busService->find($id) : null;

        return view('backend.bus.form', compact('data'));
    }

    public function search(Request $request)
    {
        $results = $this->busService->search($request->q);

        return response()->json($results);
    }
}
