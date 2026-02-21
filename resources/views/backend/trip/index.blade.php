@extends('layouts.admin')

@section('content')
    <style>
        .choices {
            margin-bottom: 0px !important;
            width: 100% !important;
            min-width: 250px;
        }

        .choices__inner {
            min-height: 38px;
        }

        .choices__list--dropdown {
            width: 100% !important;
            min-width: 100%;
        }
    </style>

    <x-admin.page-header title="Trip Schedule" :breadcrumb="[['label' => 'Dashboard', 'link' => route('backend.dashboard')], ['label' => 'Trip Schedule']]" />

    <div class="row">
        <div class="col-sm-12">
            <div class="card">

                <div class="card-body">

                    <div class="mb-2 d-flex justify-content-end align-items-center gap-3 flex-wrap">

                        {{-- Hidden Import Form --}}
                        <form action="{{ route('stop.import.confirm') }}" method="POST" enctype="multipart/form-data"
                            class="d-flex align-items-center gap-2 d-none">
                            @csrf

                            <input type="file" name="file" class="form-control form-control-sm" required>

                            <button type="submit" class="btn btn-success btn-sm">
                                Import Trip Schedule
                            </button>
                        </form>

                        {{-- Bus Search + Get Routes --}}
                        <div class="d-flex align-items-center gap-2">

                            <div class="flex-fill">
                                <select id="getFilter" class="form-select choice-select"
                                    data-url="{{ route('buses.search') }}" data-placeholder="Type to search Bus..."
                                    required>
                                </select>
                            </div>

                            <button type="button" class="btn btn-success" id="getTrips">
                                Get Trips
                            </button>

                        </div>


                        {{-- Add Trip Schedule --}}
                        <button onclick="CRUD.open()" class="btn btn-primary btn-sm" type="button">
                            Add Trip Schedule
                        </button>
                    </div>

                    <x-admin.table :headers="['#', 'Direction', 'Bus', 'Departure', 'Days', 'Auth Status', 'Status', 'Actions']"></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        CRUD.setResource("trip-schedule");

        const tableColumns = [{
                data: "id"
            },
            {
                data: "routeDirection"
            },
            {
                data: "bus"
            },
            {
                data: "departure_time"
            },
            {
                data: "days_of_week",
                render: function(data, type, row) {
                    if (!data || data.length === 0) {
                        return '-';
                    }

                    return data.map(day => `<span class="badge bg-secondary me-1">${day.toUpperCase()}</span>`)
                        .join('');
                }
            },
            {
                data: "auth_status"
            },

            CRUD.columnToggleStatus(),
            CRUD.columnActions(true, false),
        ];

        window.crudTable = CRUD.loadDataTable(tableColumns);

        document.getElementById('getTrips').addEventListener('click', function() {
            if ($("#getFilter").val() == null) {
                alert("Please select a bus first.");
                return;
            }

            if (typeof crudTable !== 'undefined' && $("#getFilter").val() != null) {
                crudTable.ajax.reload(null, false);
            }
        });
    </script>

    <script>
        document.querySelectorAll('.choice-select').forEach(select => {

            const url = select.dataset.url;
            const placeholder = select.dataset.placeholder || 'Search...';

            const choices = new Choices(select, {
                searchEnabled: true,
                searchChoices: false,
                placeholder: true,
                placeholderValue: placeholder,
                shouldSort: false,
                removeItemButton: true,
                allowHTML: true
            });

            /* ---------- PRESELECT ---------- */

            const selectedId = select.dataset.selectedId;
            const selectedName = select.dataset.selectedName;

            if (selectedId && selectedName) {
                choices.setChoices([{
                    value: selectedId,
                    label: selectedName,
                    selected: true
                }], 'value', 'label', true);
            }

            /* ---------- AJAX SEARCH ---------- */

            let debounceTimer;

            select.addEventListener('search', function(e) {

                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(async () => {

                    const value = e.detail.value;

                    if (value.length < 2) return;

                    const response = await fetch(`${url}?q=${encodeURIComponent(value)}`);
                    const data = await response.json();

                    choices.clearChoices();

                    choices.setChoices(
                        data.map(item => ({
                            value: item.id,
                            label: `<div> <span class="fw-bold">${item.bus_name}</span> <small class="text-muted">${item.bus_number ?? ''}</small> </div>`
                        })),
                        'value',
                        'label',
                        true
                    );

                }, 400);

            });

        });
    </script>
@endpush
