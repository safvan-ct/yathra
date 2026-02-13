@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="District" :breadcrumb="[['label' => 'Dashboard', 'link' => route('backend.dashboard')], ['label' => 'District']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">

                <div class="card-body">
                    <div class="mb-2 d-flex justify-content-end">
                        <form action="{{ route('district.import.preview', 1) }}" method="POST" enctype="multipart/form-data"
                            class="d-inline border p-2 me-2">
                            @csrf
                            <input type="file" name="file" required>
                            <button class="btn btn-success btn-sm">Preview Import</button>
                        </form>

                        <div class="d-inline border p-2">
                            <button onclick="CRUD.open()" class="btn btn-primary btn-sm" type="button">
                                Add District
                            </button>
                        </div>
                    </div>

                    <x-admin.table :headers="['#', 'Name', 'Code', 'Status', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        CRUD.setResource("district");

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
