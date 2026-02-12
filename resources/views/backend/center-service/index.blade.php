@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Services" :breadcrumb="[['label' => 'Dashboard', 'link' => route('backend.dashboard')], ['label' => 'Services']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <select class="form-select selectFilter form-select-sm w-auto" id="getFilter">
                        <option value="all">All Menu</option>
                        @foreach ($menus as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>

                    <button onclick="CRUD.open()" class="btn btn-primary btn-sm add-btn">Add Service</button>
                    <x-admin.table :headers="['#', 'Menu', 'Govt. Center', 'Name', 'Documents', 'Active', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery.repeater/jquery.repeater.min.js"></script>

    <script>
        $('#documentsRepeater').repeater({
            initEmpty: false,
            defaultValues: {
                'name': '',
                'note': ''
            }
        });

        let oldData =
            "Applicant OriginalPassport@@@Applicant Visacopy@@@Sponsor Visa Copy";

        oldData.split('@@@').forEach(item => {
            $('#documentsRepeater [data-repeater-create]').click();
            let last = $('#documentsRepeater [data-repeater-item]').last();
            last.find('[name="name"]').val(item);
        });
    </script>

    <script>
        CRUD.setResource("service");

        if (localStorage.getItem("MenuFilter")) {
            $('#getFilter').val(localStorage.getItem("MenuFilter"));
        }

        const tableColumns = [{
                data: "id"
            },
            {
                data: "menu.name"
            },
            {
                data: "government_center_name",
                name: 'government_center_name',
                defaultContent: ''
            },
            {
                data: "name"
            },
            {
                data: null, // action is not from DB
                orderable: false,
                searchable: false,
                render: (data, type, row) => {
                    let url1 = "{{ route('backend.document.index', ':id') }}".replace(':id', row.slug);
                    let url2 = "{{ route('backend.document-group.index', ':id') }}".replace(':id', row.slug);

                    return `<a href="${url2}" class="btn-info btn-link">Groups</a> | <a href="${url1}" class="btn-info btn-link">Documents</a>`;
                }
            },

            // CRUD.columnToggleStatus('key_service'),
            // CRUD.columnToggleStatus('useful_service'),
            CRUD.columnToggleStatus(),
            CRUD.columnActions(true, false),
        ];

        window.crudTable = CRUD.loadDataTable(tableColumns);

        $('#getFilter').on('change', function() {
            localStorage.setItem("MenuFilter", $(this).val());
            crudTable.ajax.reload(null, false);
        });

        $(document).ready(function() {
            $('#crudModal').on('shown.bs.modal', function() {
                $(this).find('.modal-dialog')
                    .removeClass('modal-sm modal-lg')
                    .addClass('modal-lg');
            });
        });
    </script>
@endpush
