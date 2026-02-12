@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Valued Partners" :breadcrumb="[['label' => 'Dashboard', 'link' => route('backend.dashboard')], ['label' => 'Valued Partners']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <button onclick="CRUD.open()" class="btn btn-primary btn-sm add-btn">Add Partner</button>
                    <x-admin.table :headers="['#', 'Name', 'Logo', 'Active', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        CRUD.setResource("partners");

        const tableColumns = [{
                data: "id"
            },
            {
                data: "name"
            },
            {
                data: "image_src",
                orderable: false,
                searchable: false,
                render: (url) => {
                    return url ?
                        `<img src="${url}" width="40" height="40" class="rounded">` :
                        '-';
                }
            },

            CRUD.columnToggleStatus(),
            CRUD.columnActions(true, false),
        ];

        window.crudTable = CRUD.loadDataTable(tableColumns);
    </script>
@endpush
