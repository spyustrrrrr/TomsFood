{{-- 
    File: resources/views/admin/reservations/index.blade.php 
    Admin Reservations Management Page
--}}

@extends('layouts.admin')

@section('title', 'Kelola Reservasi')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-calendar-check"></i> Kelola Reservasi</h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Filter Card --}}
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reservations.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                @foreach(\App\Models\Reservation::getAllStatuses() as $key => $label)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Restoran</label>
                            <select name="restaurant_id" class="form-select">
                                <option value="">Semua Restoran</option>
                                @foreach($restaurants as $restaurant)
                                    <option value="{{ $restaurant->id }}" 
                                            {{ request('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                                        {{ $restaurant->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>

                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-search"></i> Filter
                            </button>
                            <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Reservations Table --}}
            <div class="card shadow-sm">
                <div class="card-body">
                    @if($reservations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Restoran</th>
                                        <th>Tanggal & Waktu</th>
                                        <th>Tamu</th>
                                        <th>Meja</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservations as $reservation)
                                        <tr>
                                            <td>#{{ $reservation->id }}</td>
                                            <td>
                                                <strong>{{ $reservation->customer->full_name }}</strong><br>
                                                <small class="text-muted">{{ $reservation->customer->email }}</small>
                                            </td>
                                            <td>{{ $reservation->restaurant->name }}</td>
                                            <td>
                                                {{ $reservation->reservation_date->translatedFormat('d M Y') }}<br>
                                                <small class="text-muted">
                                                    {{ $reservation->reservation_date->format('H:i') }}
                                                </small>
                                            </td>
                                            <td>{{ $reservation->guest_count }} org</td>
                                            <td>
                                                @if($reservation->table_number)
                                                    <span class="badge bg-info">{{ $reservation->table_number }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{!! \App\Models\Reservation::getStatusBadge($reservation->status) !!}</td>
                                            <td>
                                                @if($reservation->order)
                                                    {{ $reservation->order->formatted_total }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.reservations.show', $reservation->id) }}" 
                                                       class="btn btn-outline-primary"
                                                       title="Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>

                                                    @if($reservation->status == 'pending')
                                                        <form action="{{ route('admin.reservations.confirm', $reservation->id) }}" 
                                                              method="POST" 
                                                              class="d-inline">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="btn btn-outline-success"
                                                                    title="Konfirmasi">
                                                                <i class="bi bi-check-circle"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center mt-4">
                            {{ $reservations->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle fs-1 d-block mb-3"></i>
                            <h5>Tidak ada data reservasi</h5>
                            <p class="mb-0">Belum ada reservasi sesuai filter yang dipilih.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection