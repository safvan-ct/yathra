@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info dashnum-card dashnum-card-small text-white overflow-hidden">
                <span class="round bg-info small"></span>
                <span class="round bg-info big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg">
                            <i class="text-white ti ti-calendar"></i>
                        </div>
                        <div class="ms-2">
                            <h4 class="text-white mb-1">{{ $totalBuses }}</h4>
                            <p class="mb-0 opacity-75 text-sm">Total Buses</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card bg-success dashnum-card dashnum-card-small text-white overflow-hidden">
                <span class="round bg-success small"></span>
                <span class="round bg-success big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg">
                            <i class="text-white ti ti-calendar"></i>
                        </div>
                        <div class="ms-2">
                            <h4 class="text-white mb-1">{{ $totalStops }}</h4>
                            <p class="mb-0 opacity-75 text-sm">Total Stops</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-12">
            <div class="card bg-primary-dark dashnum-card dashnum-card-small text-white overflow-hidden">
                <span class="round bg-primary small"></span>
                <span class="round bg-primary big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg">
                            <i class="text-white ti ti-credit-card"></i>
                        </div>
                        <div class="ms-2">
                            <h4 class="text-white mb-1">{{ $totalRoutes }}</h4>
                            <p class="mb-0 opacity-75 text-sm">Total Routes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-12">
            <div class="card dashnum-card dashnum-card-small overflow-hidden">
                <span class="round bg-warning small"></span>
                <span class="round bg-warning big"></span>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="avtar avtar-lg bg-light-warning">
                            <i class="text-warning ti ti-briefcase"></i>
                        </div>
                        <div class="ms-2">
                            <h4 class="mb-1">{{ $totalTrips }}</h4>
                            <p class="mb-0 opacity-75 text-sm">Total Trips</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="card">
                <h4 class="card-header">Stop Suggestions</h4>

                <div class="card-body">
                    <select class="form-select selectFilter form-select-sm w-auto" id="getFilter">
                        <option value="all">All</option>
                        <option value="1" selected>New</option>
                        <option value="2">Opened</option>
                        <option value="3">Closed</option>
                    </select>

                    <x-admin.table :headers="['#', 'Name', 'Email', 'Phone', 'Message', 'Booking From', 'Status']" class=''></x-admin.table>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .row-1 {
        background-color: #fff !important;
        /* light blue */
    }

    .row-2 {
        background-color: #fff3cd !important;
        /* light orange */
    }

    .row-3 {
        background-color: #d1e7dd !important;
        /* light green */
    }
</style>

@push('scripts')
    <script>

    </script>
@endpush
