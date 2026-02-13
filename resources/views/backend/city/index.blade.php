@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Cities" :breadcrumb="[['label' => 'Dashboard', 'link' => route('backend.dashboard')], ['label' => 'Cities']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">

                <div class="card-body">
                    <div class="mb-2 d-flex justify-content-end">
                        <form action="{{ route('city.import.confirm') }}" method="POST" enctype="multipart/form-data"
                            class="d-inline border p-2 me-2">
                            @csrf
                            <input type="file" name="file" required>
                            <button class="btn btn-success btn-sm">Import Cities</button>
                        </form>

                        <div class="d-inline border p-2">
                            <button onclick="CRUD.open()" class="btn btn-primary btn-sm" type="button">
                                Add City
                            </button>
                        </div>
                    </div>

                    <x-admin.table :headers="['#', 'District', 'Name', 'Status', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        CRUD.setResource("city");

        const tableColumns = [{
                data: "id"
            },
            {
                data: "district"
            },
            {
                data: "name"
            },

            CRUD.columnToggleStatus(),
            CRUD.columnActions(true, false),
        ];

        window.crudTable = CRUD.loadDataTable(tableColumns);
    </script>
@endpush
