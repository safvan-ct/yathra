@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Menu" :breadcrumb="[['label' => 'Dashboard', 'link' => route('backend.dashboard')], ['label' => 'Menu']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <button onclick="CRUD.open()" class="btn btn-primary btn-sm add-btn">Add Menu</button>
                    <x-admin.table :headers="['#', 'Name', 'Slug', 'Active', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        CRUD.setResource("menu");

        const tableColumns = [{
                data: "id"
            },
            {
                data: "name"
            },
            {
                data: "slug"
            },

            CRUD.columnToggleStatus(),
            CRUD.columnActions(true, false),
        ];

        window.crudTable = CRUD.loadDataTable(tableColumns);
    </script>
@endpush
