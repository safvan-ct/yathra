@extends('layouts.operator')

@section('title', 'Bus')

@push('styles')
    <link href="{{ asset('operator/css/fleet.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="top-nav-tabs d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Manage Bus</h5>

        <button class="btn btn-primary btn-sm rounded-pill px-3" style="background: var(--primary-gradient);"
            data-bs-toggle="modal" data-bs-target="#addBusModal">
            <i class="bi bi-plus-lg"></i> Add Bus
        </button>
    </div>

    <div class="container mt-3">
        <div class="row pb-4 mb-5">
            @foreach ($buses as $item)
                <div class="col-12 col-md-4">
                    <div class="bus-card p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="fw-bold mb-0">{{ $item->bus_number }}</h6>
                                <small class="text-muted">Name: {{ $item->bus_name }}</small>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button class="btn btn-assign me-2" data-bs-toggle="modal" data-bs-target="#addBusModal"
                                    data-bus_name="{{ $item->bus_name }}" data-bus_id="{{ $item->id }}"
                                    data-bus_number="{{ $item->bus_number }}" data-bus_color="{{ $item->bus_color }}">
                                    <i class="bi bi-pencil me-1"></i> Edit Bus
                                </button>

                                {{-- <div class="dropdown">
                                    <button class="btn btn-light btn-sm text-muted" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        <li>
                                            <a class="dropdown-item text-danger" href="#">
                                                <i class="bi bi-trash me-2"></i> De-acivate Bus
                                            </a>
                                        </li>
                                    </ul>
                                </div> --}}
                            </div>
                        </div>

                        @php
                            $colors = [
                                'info' => 'Blue',
                                'danger' => 'Red',
                                'success' => 'Green',
                            ];
                        @endphp

                        <div class="row g-2 mt-2 bg-light rounded-3 p-2">
                            <div class="col-4">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">COLOR</small>
                                <span
                                    class="small text-uppercase fw-bold text-{{ $item->bus_color }}">{{ $colors[$item->bus_color] }}</span>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">STATUS</small>
                                <span
                                    class="small fw-bold text-uppercase text-{{ $item->is_active ? 'success' : 'danger' }}">
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="col-4">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">APPROVAL</small>
                                <span
                                    class="small fw-bold text-{{ $item->auth_status->value == 'approved' ? 'success' : ($item->auth_status->value == 'pending' ? 'warning' : 'danger') }}">
                                    {{ strtoupper($item->auth_status->value) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="modal fade" id="addBusModal" tabindex="-1" aria-labelledby="addBusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 25px;">
                <div class="modal-header text-white"
                    style="background: var(--primary-gradient); border-radius: 25px 25px 0 0; padding: 25px;">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-25 rounded-2 p-2 me-3">
                            <i class="bi bi-bus-front fs-4"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold" id="addBusModalLabel">Register New Bus</h5>
                            <p class="small mb-0 opacity-75">Add a vehicle to your list</p>
                        </div>
                    </div>

                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <form action="{{ route('operator.bus.store') }}" method="POST" id="form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Bus Registration
                                Number</label>
                            <input type="text" class="form-control form-control-lg border-2 shadow-sm"
                                placeholder="e.g. KL 01 AB 1234" style="border-radius: 12px; font-size: 1rem;" required
                                name="bus_number" id="bus_number" value="">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary text-uppercase">Bus Name</label>
                            <input type="text" class="form-control form-control-lg border-2 shadow-sm"
                                placeholder="e.g. KSRTC" style="border-radius: 12px; font-size: 1rem;" required
                                name="bus_name" id="bus_name">
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <label class="form-label small fw-bold text-secondary text-uppercase">Bus Color</label>
                                <select class="form-select border-2 shadow-sm" style="border-radius: 12px; padding: 12px;"
                                    required name="bus_color" id="bus_color">
                                    <option value="info">Blue</option>
                                    <option value="danger">Red</option>
                                    <option value="success">Green</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-lg text-white fw-bold py-3"
                                style="background: var(--primary-gradient); border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);">
                                Save & Add to List
                            </button>
                            <button type="button" class="btn btn-link text-muted text-decoration-none small"
                                data-bs-dismiss="modal">
                                Discard Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            var assignModal = document.getElementById('addBusModal');

            assignModal.addEventListener('show.bs.modal', function(event) {

                var button = event.relatedTarget;

                var busId = button.getAttribute('data-bus_id') ?? 0;
                var busName = button.getAttribute('data-bus_name') ?? '';
                var busNumber = button.getAttribute('data-bus_number') ?? '';
                var busColor = button.getAttribute('data-bus_color') ?? '';

                if (busId != 0) {
                    let url = "{{ route('operator.bus.update', ':id') }}".replace(':id', busId);

                    var form = document.getElementById('form');

                    form.action = url;
                    form.method = 'POST';

                    var methodInput = document.getElementById('method_field');

                    if (!methodInput) {
                        methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.id = 'method_field';
                        form.appendChild(methodInput);
                    }

                    methodInput.value = 'PUT';

                    document.getElementById('bus_name').value = busName;
                    document.getElementById('bus_number').value = busNumber;
                    document.getElementById('bus_color').value = busColor;
                }
            });
        });
    </script>
@endpush
