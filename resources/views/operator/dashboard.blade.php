@extends('layouts.operator')

@section('title', Auth::guard('operator')->user()->name)

@push('styles')
    <link href="{{ asset('operator/css/dashboard.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="dash-header">
        <div class="container-custom d-flex justify-content-between align-items-center">
            <div>
                <h4 class="fw-bold mb-0">Hello, {{ Auth::guard('operator')->user()->name }} 👋</h4>
                <p class="small opacity-75 mb-0">
                    Account Status:
                    @if (Auth::guard('operator')->user()->auth_status == \App\Enums\OperatorAuthStatus::PENDING)
                        <span class="badge bg-warning">Pending</span>
                    @elseif (Auth::guard('operator')->user()->auth_status == \App\Enums\OperatorAuthStatus::APPROVED)
                        <span class="badge bg-success">Approved</span>
                    @else
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </p>
            </div>
            <div class="position-relative">
                <i class="bi bi-bell-fill fs-4"></i>
                <span
                    class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
            </div>
        </div>
    </div>

    <div class="container-custom">

        <div class="stat-grid">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="stat-card">
                        <div class="icon-circle bg-bus"><i class="bi bi-bus-front"></i></div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ Auth::guard('operator')->user()->buses->count() }}</h3>
                            <small class="text-muted">Total Buses</small>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="stat-card">
                        <div class="icon-circle bg-driver"><i class="bi bi-arrow-left-right"></i></div>
                        <div>
                            <h3 class="fw-bold mb-0">18</h3>
                            <small class="text-muted">Total Trips</small>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="stat-card">
                        <div class="icon-circle bg-staff"><i class="bi bi-people"></i></div>
                        <div>
                            <h3 class="fw-bold mb-0">12</h3>
                            <small class="text-muted">On-Duty Staff</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-3 mt-4">
            <h6 class="fw-bold text-secondary mb-3">OPERATIONS</h6>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <a href="routes.html" class="action-btn d-block">
                        <i class="bi bi-map"></i>
                        Routes
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="schedules.html" class="action-btn d-block">
                        <i class="bi bi-calendar-check"></i>
                        Schedules
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="live.html" class="action-btn d-block">
                        <i class="bi bi-geo-alt"></i>
                        Live Tracks
                    </a>
                </div>
                <div class="col-6 col-md-3">
                    <a href="#" class="action-btn d-block">
                        <i class="bi bi-signpost-split"></i>
                        Suggest Routes / Stops
                    </a>
                </div>
            </div>
        </div>

        <div class="px-3 mt-4 pb-3">
            <h6 class="fw-bold text-secondary mb-3">LIVE ALERTS</h6>
            <div class="list-group border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                    <div>
                        <small class="text-danger fw-bold d-block">OVER-SPEEDING</small>
                        <span class="text-dark small">Bus DL-01-4432 (Driver: Rajesh)</span>
                    </div>
                    <span class="badge bg-light text-muted">2m ago</span>
                </div>
                <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                    <div>
                        <small class="text-primary fw-bold d-block">TRIP STARTED</small>
                        <span class="text-dark small">Bus MH-12-1100 on Route 402</span>
                    </div>
                    <span class="badge bg-light text-muted">15m ago</span>
                </div>
            </div>
        </div>
    </div>


@endsection
