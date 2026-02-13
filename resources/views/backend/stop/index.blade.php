@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Stop" :breadcrumb="[['label' => 'Dashboard', 'link' => route('backend.dashboard')], ['label' => 'Stop']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">

                <div class="card-body">
                    <div class="mb-2 d-flex justify-content-end">
                        <form action="{{ route('backend.stop.import.preview') }}" method="POST" enctype="multipart/form-data"
                            class="d-inline border p-2">
                            @csrf
                            <input type="file" name="file" required>
                            <button class="btn btn-success btn-sm">Preview Import</button>
                            <button onclick="CRUD.open()" class="btn btn-primary btn-sm" type="button">Add Stop</button>
                        </form>
                    </div>

                    <button onclick="CRUD.open()" class="btn btn-primary btn-sm add-btn d-none">Add Stop</button>
                    <x-admin.table :headers="['#', 'Name', 'Code', 'Status', 'Actions']"></x-admin.table>
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

            CRUD.columnToggleStatus(),
            CRUD.columnActions(true, false),
        ];

        window.crudTable = CRUD.loadDataTable(tableColumns);
    </script>
@endpush
