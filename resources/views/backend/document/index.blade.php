@extends('layouts.admin')

@section('content')
    @php
        $title = '<span class="text-primary">' . e($service->name) . '</span> - Required Documents';
    @endphp

    <x-admin.page-header :title="$title" :breadcrumb="[
        [
            'label' => 'Dashboard',
            'link' => route('backend.dashboard'),
        ],
        [
            'label' => 'Services',
            'link' => route('backend.service.index'),
        ],
        ['label' => $service->name],
    ]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <input type="hidden" id="getFilter" value="{{ $service->id }}">
                    <button onclick="CRUD.open(0, {{ $service->id }})" class="btn btn-primary btn-sm add-btn">
                        Add Document
                    </button>

                    <x-admin.table :headers="['#', 'Name', 'Notes', 'Active', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        CRUD.setResource("document");

        const tableColumns = [{
                data: "id"
            },
            {
                data: "name",
                defaultContent: ''
            },
            {
                data: "notes",
            },

            CRUD.columnToggleStatus(),
            CRUD.columnActions(true, false, 'dataTable', '{{ $service->id }}'),
        ];

        window.crudTable = CRUD.loadDataTable(tableColumns);
    </script>
@endpush
