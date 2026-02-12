@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Stop" :breadcrumb="[['label' => 'Dashboard', 'link' => route('backend.dashboard')], ['label' => 'Stop']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <button onclick="CRUD.open()" class="btn btn-primary btn-sm add-btn">Add Stop</button>
                    <x-admin.table :headers="['#', 'Name', 'Code', 'Bus Station', 'Status', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        CRUD.setResource("stop");

        const tableColumns = [{
                data: "id"
            },
            {
                data: "name"
            },
            {
                data: "code"
            },

            CRUD.columnToggleStatus("is_bus_terminal"),

            CRUD.columnToggleStatus(),
            CRUD.columnActions(true, false),
        ];

        window.crudTable = CRUD.loadDataTable(tableColumns);
    </script>
@endpush
