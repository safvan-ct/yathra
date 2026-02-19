@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Bus" :breadcrumb="[['label' => 'Dashboard', 'link' => route('backend.dashboard')], ['label' => 'Bus']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">

                <div class="card-body">
                    <div class="mb-2 d-flex justify-content-end">
                        <form action="{{ route('stop.import.confirm') }}" method="POST" enctype="multipart/form-data"
                            class="d-inline border p-2 me-2 d-none">
                            @csrf
                            <input type="file" name="file" required>
                            <button class="btn btn-success btn-sm">Import Bus</button>
                        </form>

                        <div class="d-inline border p-2">
                            <button onclick="CRUD.open()" class="btn btn-primary btn-sm" type="button">
                                Add Bus
                            </button>
                        </div>
                    </div>

                    <x-admin.table :headers="['#', 'Name', 'Number', 'Operator', 'Status', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        CRUD.setResource("bus");

        const tableColumns = [{
                data: "id"
            },
            {
                data: "bus_name"
            },
            {
                data: "bus_number"
            },
            {
                data: "operator"
            },

            CRUD.columnToggleStatus(),
            CRUD.columnActions(true, false),
        ];

        window.crudTable = CRUD.loadDataTable(tableColumns);
    </script>
@endpush
