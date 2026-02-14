@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Route" :breadcrumb="[['label' => 'Dashboard', 'link' => route('backend.dashboard')], ['label' => 'Route']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <button onclick="CRUD.open()" class="btn btn-primary btn-sm add-btn">Add Route</button>
                    <x-admin.table :headers="[
                        '#',
                        'Name',
                        'Info',
                        'Origin Stop',
                        'Destination Stop',
                        'Status',
                        'Actions',
                        'Add Stop',
                    ]"></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        CRUD.setResource("route-pattern");

        const tableColumns = [{
                data: "id"
            },
            {
                data: "name"
            },
            {
                data: "info"
            },
            {
                data: "origin_stop",
                name: "origin_stop"
            },
            {
                data: "destination_stop",
                name: "destination_stop"
            },

            CRUD.columnToggleStatus(),
            CRUD.columnActions(true, false),
            {
                data: null,
                orderable: false,
                searchable: false,
                render: (data, type, row) => {
                    let route = "{{ route('backend.route-pattern-stop.index', ':id') }}";
                    return `
                        <a class="btn btn-link text-danger" href="${route.replace(':id', row.id)}">Add Stop</a>
                    `;
                },
            }
        ];

        window.crudTable = CRUD.loadDataTable(tableColumns);
    </script>
@endpush
