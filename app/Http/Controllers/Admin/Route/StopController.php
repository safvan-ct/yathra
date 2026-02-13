<?php
namespace App\Http\Controllers\Admin\Route;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StopCreateRequest;
use App\Models\Stop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class StopController extends Controller
{
    public function index()
    {
        return view('backend.stop.index');
    }

    public function dataTable(Request $request)
    {
        return DataTables::of(Stop::select('id', 'name', 'code', 'is_active'))->make(true);
    }

    public function form($id = null)
    {
        $data = $id ? Stop::findOrFail($id) : null;

        return view('backend.stop.form', compact('data'));
    }

    public function store(StopCreateRequest $request)
    {
        $data = $this->getData($request);

        // $code = $this->generateStopCode($data['code']);

        // $data['code'] = $code;

        Stop::create($data);

        return response()->json(['message' => 'Stop added successfully']);
    }

    public function update(StopCreateRequest $request, Stop $stop)
    {
        $data = $this->getData($request);

        // $code = explode('-', $stop->code ?? '')[0] == $data['code'] ? $stop->code : $this->generateStopCode($data['code']);

        // $data['code'] = $code;

        $stop->update($data);

        return response()->json(['message' => 'Stop updated successfully']);
    }

    public function toggleStatus(Request $request, $id)
    {
        // $inUse = DB::table('route_direction_stops')
        //     ->where('stop_id', $stop->id)
        //     ->exists();

        // if ($inUse) {
        //     return back()->with('error', 'Stop is used in routes. Cannot delete.');
        // }

        $column = $request->column ?? 'is_active';
        $item   = Stop::findOrFail($id);

        $item->$column = ! $item->$column;
        $item->save();

        return response()->json(['message' => 'Updated successfully']);
    }

    public function importPreview(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);

        $file = fopen($request->file('file')->getRealPath(), 'r');

        $header = fgetcsv($file);

        $expected = [
            'name', 'code', 'local_body', 'assembly', 'district', 'state', 'pincode', 'latitude', 'longitude', 'is_bus_terminal', 'is_active',
        ];

        if ($header !== $expected) {
            return back()->with('error', 'Invalid CSV header format.');
        }

        $preview   = [];
        $errors    = [];
        $rowNumber = 1;

        while (($row = fgetcsv($file)) !== false) {

            $rowNumber++;

            $data = array_combine($header, $row);

            $validator = Validator::make($data, [
                'name'            => 'required|string|max:255',
                'code'            => 'required|string|max:50',
                'local_body'      => 'nullable|string|max:255',
                'assembly'        => 'nullable|string|max:255',
                'district'        => 'nullable|string|max:255',
                'state'           => 'nullable|string|max:255',
                'pincode'         => 'nullable',
                'latitude'        => 'nullable',
                'longitude'       => 'nullable',
                'is_bus_terminal' => 'nullable|boolean',
                'is_active'       => 'nullable|boolean',
            ]);

            $exists = Stop::where('code', $data['code'])->exists();

            $preview[] = [
                'data'      => $data,
                'valid'     => ! $validator->fails() && ! $exists,
                'errors'    => $validator->errors()->all(),
                'duplicate' => $exists,
            ];
        }

        fclose($file);

        Session::put('stop_import_preview', $preview);

        return view('backend.stop.import_preview', compact('preview'));
    }

    public function importConfirm()
    {
        $preview = Session::get('stop_import_preview');

        if (! $preview) {
            return redirect()->route('backend.stop.index')->with('error', 'No import session found.');
        }

        $insertData = [];
        $codes      = [];

        foreach ($preview as $row) {

            if (! $row['valid']) {
                continue;
            }

            $data    = $row['data'];
            $codes[] = $data['code'];
            $code    = $this->generateStopCode($data['code'], $codes);

            $insertData[] = [
                'name'            => $data['name'],
                'code'            => $data['code'],

                'local_body'      => empty($data['local_body']) || $data['local_body'] == 'NULL' ? null : $data['local_body'],
                'assembly'        => empty($data['assembly']) || $data['assembly'] == 'NULL' ? null : $data['assembly'],
                'district'        => empty($data['district']) || $data['district'] == 'NULL' ? null : $data['district'],
                'state'           => empty($data['state']) || $data['state'] == 'NULL' ? null : $data['state'],
                'pincode'         => empty($data['pincode']) || $data['pincode'] == 'NULL' ? null : $data['pincode'],

                'latitude'        => empty($data['latitude']) || $data['latitude'] == 'NULL' ? null : $data['latitude'],
                'longitude'       => empty($data['longitude']) || $data['longitude'] == 'NULL' ? null : $data['longitude'],

                'is_bus_terminal' => $data['is_bus_terminal'] ?? 0,
                'is_active'       => $data['is_active'] ?? 1,

                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        try {
            DB::transaction(function () use ($insertData) {
                Stop::insert($insertData);
            });
        } catch (\Throwable $e) {
            return redirect()->route('backend.stop.index')->with('error', 'Import failed: ' . $e->getMessage());
        }

        Session::forget('stop_import_preview');

        return redirect()->route('backend.stop.index')->with('success', 'Stops imported successfully.');
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,txt']);

        $file = fopen($request->file('file')->getRealPath(), 'r');

        $header = fgetcsv($file);

        $requiredHeaders = [
            'name', 'code', 'local_body', 'assembly', 'district', 'state', 'pincode', 'latitude', 'longitude', 'is_bus_terminal', 'is_active',
        ];

        if ($header !== $requiredHeaders) {
            return back()->with('error', 'Invalid CSV header format.');
        }

        $errors     = [];
        $insertData = [];
        $rowNumber  = 1;
        $codes      = [];

        while (($row = fgetcsv($file)) !== false) {

            $rowNumber++;

            $data = array_combine($header, $row);

            $validator = Validator::make($data, [
                'name'            => 'required|string|max:255',
                'code'            => 'required|string|max:50',
                'local_body'      => 'nullable|string|max:255',
                'assembly'        => 'nullable|string|max:255',
                'district'        => 'nullable|string|max:255',
                'state'           => 'nullable|string|max:255',
                'pincode'         => 'nullable',
                'latitude'        => 'nullable',
                'longitude'       => 'nullable',
                'is_bus_terminal' => 'nullable|boolean',
                'is_active'       => 'nullable|boolean',
            ]);

            if ($validator->fails()) {
                $errors[] = "Row {$rowNumber}: " . implode(', ', $validator->errors()->all());
                continue;
            }

            $codes[] = strtoupper(preg_replace('/[^A-Za-z]/', '', $data['code']));

            $insertData[] = [
                'name'            => $data['name'],
                'code'            => $this->generateStopCode($data['code'], $codes),

                'local_body'      => empty($data['local_body']) || $data['local_body'] == 'NULL' ? null : $data['local_body'],
                'assembly'        => empty($data['assembly']) || $data['assembly'] == 'NULL' ? null : $data['assembly'],
                'district'        => empty($data['district']) || $data['district'] == 'NULL' ? null : $data['district'],
                'state'           => empty($data['state']) || $data['state'] == 'NULL' ? null : $data['state'],
                'pincode'         => empty($data['pincode']) || $data['pincode'] == 'NULL' ? null : $data['pincode'],

                'latitude'        => empty($data['latitude']) || $data['latitude'] == 'NULL' ? null : $data['latitude'],
                'longitude'       => empty($data['longitude']) || $data['longitude'] == 'NULL' ? null : $data['longitude'],

                'is_bus_terminal' => $data['is_bus_terminal'] ?? 0,
                'is_active'       => $data['is_active'] ?? 1,

                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        fclose($file);

        if (! empty($errors)) {
            return back()->with('error', implode('<br>', $errors));
        }

        if (count($insertData) === 0) {
            return back()->with('error', 'No valid rows found.');
        }

        DB::beginTransaction();

        try {

            collect($insertData)->chunk(500)->each(function ($chunk) {
                Stop::insert($chunk->toArray());
            });

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }

        return back()->with('success', 'Stops imported successfully.');
    }

    private function getData(StopCreateRequest $request)
    {
        return [
            'name'       => $request->name,
            'code'       => $request->code,
            'local_body' => $request->local_body,
            'assembly'   => $request->assembly,
            'district'   => $request->district,
            'state'      => $request->state,
            'pincode'    => $request->pincode,
            'latitude'   => $request->latitude,
            'longitude'  => $request->longitude,
        ];
    }

    private function generateStopCode($name, $codes = [])
    {
        $prefix = strtoupper(preg_replace('/[^A-Za-z]/', '', $name));

        $count     = empty($codes) ? 0 : array_count_values($codes)[$prefix] ?? 0;
        $codeCount = $count > 1 ? $count : 0;

        $count     = Stop::where('code', 'like', $prefix . '-%')->count();
        $stopCount = $count > 1 ? $count + 1 : 1;

        $lastStop = Stop::where('code', 'like', $prefix . '-%')->orderBy('code', 'desc')->first();

        if ($lastStop && $stopCount > 1) {
            $lastNumber = (int) substr($lastStop->code, -3);
            $nextNumber = str_pad($lastNumber + 1 + $codeCount, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = $codeCount > 0 ? str_pad($codeCount, 3, '0', STR_PAD_LEFT) : '001';
        }

        return $prefix . '-' . $nextNumber;
    }
}
