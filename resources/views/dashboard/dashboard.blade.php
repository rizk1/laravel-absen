@extends('layout.app-layout')
@section('title-page', 'Dashboard - ')
@section('title-content', 'Dashboard')

@section('css')
<style>
    .filter-card {
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .filter-label {
        font-weight: bold;
        margin-right: 10px;
    }
</style>
@endsection

@section('content')
@if ($isAdmin)
    <h2>All Users Data</h2>
@else
    <h2>Personal Data</h2>
@endif

<div class="row my-4">
    <div class="col-12">
        <div class="card filter-card">
            <div class="card-body">
                <form id="filter-form" action="{{ route('dashboard') }}" method="GET" class="form-inline justify-content-center">
                    <div class="form-group mb-2">
                        <label for="filter" class="filter-label">Filter Data:</label>
                        <select name="filter" id="filter" class="form-control mr-2">
                            <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>Semua Hari</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mb-2">Terapkan Filter</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-{{ $isAdmin ? '3' : '4' }} col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-primary">
                <i class="fas fa-fingerprint"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Absen Masuk</h4>
                </div>
                <div class="card-body">
                    {{ $filter == 'today' ? $absenMasuk : $totalAllDays['masuk'] }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-{{ $isAdmin ? '3' : '4' }} col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-danger">
                <i class="fas fa-fingerprint"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Absen Pulang</h4>
                </div>
                <div class="card-body">
                    {{ $filter == 'today' ? $absenPulang : $totalAllDays['pulang'] }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-{{ $isAdmin ? '3' : '4' }} col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="fas fa-fingerprint"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Absen Lembur</h4>
                </div>
                <div class="card-body">
                    {{ $filter == 'today' ? $absenLembur : $totalAllDays['lembur'] }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-{{ $isAdmin ? '3' : '4' }} col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-warning">
                <i class="fas fa-fingerprint"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Absen Total</h4>
                </div>
                <div class="card-body">
                    {{ $filter == 'today' ? $totalAbsen : $totalAllDays['total'] }}
                </div>
            </div>
        </div>
    </div>
    @if ($isAdmin)
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
            <div class="card-icon bg-success">
                <i class="fas fa-users"></i>
            </div>
            <div class="card-wrap">
                <div class="card-header">
                    <h4>Total Users</h4>
                </div>
                <div class="card-body">
                    {{ $totalUsers }}
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

@section('js')
<script src="{{asset('assets/modules/datatables/datatables.min.js')}}"></script>
<script src="{{asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/modules/jquery-ui/jquery-ui.min.js')}}"></script>
@endsection