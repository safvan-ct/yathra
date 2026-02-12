@extends('layouts.admin')

@section('content')
    <x-admin.page-header title="Govt. Centers" :breadcrumb="[['label' => 'Dashboard', 'link' => route('backend.dashboard')], ['label' => 'Govt. Centers']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div id="documentsRepeater">
                        <div data-repeater-list="documents">
                            <div data-repeater-item class="row g-2 mb-2">
                                <div class="col-md-5">
                                    <input type="text" name="name" class="form-control" placeholder="Document name">
                                </div>

                                <div class="col-md-6">
                                    <input type="text" name="note" class="form-control" placeholder="Note">
                                </div>

                                <div class="col-md-1">
                                    <button type="button" data-repeater-delete class="btn btn-danger btn-sm">✕</button>
                                </div>
                            </div>
                        </div>

                        <button type="button" data-repeater-create class="btn btn-primary btn-sm mt-2">
                            + Add Document
                        </button>
                    </div>


                    <select class="form-select selectFilter form-select-sm w-auto" id="getFilter">
                        <option value="all">All Menu</option>
                        @foreach ($menus as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>

                    <button onclick="CRUD.open()" class="btn btn-primary btn-sm add-btn">Add Govt. Center</button>
                    <x-admin.table :headers="['#', 'Menu', 'Name', 'Tagline', 'Active', 'Actions']"></x-admin.table>
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
        CRUD.setResource("govt-center");

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
                data: "name"
            },
            {
                data: "tagline"
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
