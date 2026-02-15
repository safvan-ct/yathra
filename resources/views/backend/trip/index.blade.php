@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Trip Schedule" :breadcrumb="[['label' => 'Dashboard', 'link' => route('backend.dashboard')], ['label' => 'Trip Schedule']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">

                <div class="card-body">
                    <div class="mb-2 d-flex justify-content-end">
                        <form action="{{ route('stop.import.confirm') }}" method="POST" enctype="multipart/form-data"
                            class="d-inline border p-2 me-2 d-none">
                            @csrf
                            <input type="file" name="file" required>
                            <button class="btn btn-success btn-sm">Import Trip Schedule</button>
                        </form>

                        <div class="d-inline border p-2">
                            <button onclick="CRUD.open()" class="btn btn-primary btn-sm" type="button">
                                Add Trip Schedule
                            </button>
                        </div>
                    </div>

                    <x-admin.table :headers="['#', 'Direction', 'Bus', 'Departure', 'Days', 'Status', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        CRUD.setResource("trip-schedule");

        const tableColumns = [{
                data: "id"
            },
            {
                data: "routeDirection"
            },
            {
                data: "bus"
            },
            {
                data: "departure_time"
            },
            {
                data: "days_of_week",
                render: function(data, type, row) {

                    if (!data || data.length === 0) {
                        return '-';
                    }

                    return data.map(day =>
                        `<span class="badge bg-secondary me-1">
                ${day.toUpperCase()}
            </span>`
                    ).join('');
                }
            },

            CRUD.columnToggleStatus(),
            CRUD.columnActions(true, false),
        ];

        window.crudTable = CRUD.loadDataTable(tableColumns);
    </script>
@endpush
