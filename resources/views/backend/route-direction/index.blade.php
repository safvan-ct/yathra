@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Route Directions" :breadcrumb="[['label' => 'Dashboard', 'link' => route('backend.dashboard')], ['label' => 'Route Directions']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">

                <div class="card-body">
                    <div class="mb-2 d-flex justify-content-end">
                        <form action="{{ route('route-direction.import.preview') }}" method="POST"
                            enctype="multipart/form-data" class="d-inline border p-1 me-2">
                            @csrf
                            <input type="file" name="file" required>
                            <button class="btn btn-success btn-sm">Preview Import Directions</button>
                        </form>

                        <form action="{{ route('route-direction-stop.import.preview') }}" method="POST"
                            enctype="multipart/form-data" class="d-inline border p-1 me-2">
                            @csrf
                            <input type="file" name="file" required>
                            <button class="btn btn-success btn-sm" type="submit">Preview Import Stops</button>
                        </form>

                        <div class="d-inline border p-1">
                            <button onclick="CRUD.open()" class="btn btn-primary btn-sm" type="button">
                                Add Route Directions
                            </button>
                        </div>
                    </div>

                    <x-admin.table :headers="['#', 'Route', 'Name', 'Direction', 'Stops', 'Status', 'Actions', 'Add Stop']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        CRUD.setResource("route-direction");

        const tableColumns = [{
                data: "id"
            },
            {
                data: "routePattern"
            },
            {
                data: "name"
            },
            {
                data: "direction"
            },
            {
                data: "stops",
                name: 'stops'
            },

            CRUD.columnToggleStatus(),
            CRUD.columnActions(true, false),

            {
                data: null,
                orderable: false,
                searchable: false,
                render: (data, type, row) => {
                    let route = "{{ route('route-direction-stop.index', ':id') }}";
                    return `
                        <a class="btn btn-link text-danger" href="${route.replace(':id', row.id)}">Add Stop</a>
                    `;
                },
            }
        ];

        window.crudTable = CRUD.loadDataTable(tableColumns);
    </script>
@endpush
