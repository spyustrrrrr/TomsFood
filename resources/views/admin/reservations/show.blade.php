{{-- 
    File: resources/views/admin/reservations/show.blade.php 
    Admin Reservation Detail & Management Page
--}}

@extends('layouts.admin')

@section('title', 'Detail Reservasi #' . $reservation->id)

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8">
            {{-- Back Button --}}
            <div class="mb-3">
                <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
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

            {{-- Reservation Details --}}
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="bi bi-receipt"></i> Reservasi #{{ $reservation->id }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Customer</label>
                            <h5>{{ $reservation->customer->full_name }}</h5>
                            <p class="mb-0 text-muted">{{ $reservation->customer->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Restoran</label>
                            <h5>{{ $reservation->restaurant->name }}</h5>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <i class="bi bi-calendar-event text-primary"></i>
                            <strong>Tanggal & Waktu:</strong><br>
                            {{ $reservation->formatted_date }}
                        </div>
                        <div class="col-md-6 mb-3">
                            <i class="bi bi-people text-primary"></i>
                            <strong>Jumlah Tamu:</strong><br>
                            {{ $reservation->guest_count }} orang
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <i class="bi bi-table text-primary"></i>
                            <strong>Nomor Meja:</strong><br>
                            @if($reservation->table_number)
                                <span class="badge bg-info fs-6">{{ $reservation->table_number }}</span>
                            @else
                                <span class="text-muted">Belum ditentukan</span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <i class="bi bi-bookmark text-primary"></i>
                            <strong>Status:</strong><br>
                            {!! \App\Models\Reservation::getStatusBadge($reservation->status) !!}
                        </div>
                    </div>

                    @if($reservation->special_request)
                        <hr>
                        <div class="mb-3">
                            <i class="bi bi-chat-dots text-primary"></i>
                            <strong>Permintaan Khusus:</strong><br>
                            <p class="mb-0 mt-2 p-3 bg-light rounded">{{ $reservation->special_request }}</p>
                        </div>
                    @endif

                    <hr>

                    <div class="text-muted small">
                        <i class="bi bi-clock"></i> Dibuat: {{ $reservation->created_at->translatedFormat('d F Y, H:i') }}
                    </div>
                </div>
            </div>

            {{-- Pre-Order Items --}}
            @if($reservation->order && $reservation->order->orderItems->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-cart-check"></i> Pre-Order Menu</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Menu</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservation->order->orderItems as $item)
                                        <tr>
                                            <td>{{ $item->menu_name }}</td>
                                            <td class="text-center">{{ $item->quantity }}</td>
                                            <td class="text-end">{{ $item->formatted_total }}</td>
                                            <td class="text-end">
                                                <strong>Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td class="text-end">
                                            <h5 class="mb-0 text-primary">
                                                {{ $reservation->order->formatted_total }}
                                            </h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end text-muted">Komisi (7%):</td>
                                        <td class="text-end text-muted">
                                            Rp {{ number_format($reservation->order->total * 0.07, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end text-muted">Diterima Restoran:</td>
                                        <td class="text-end text-success">
                                            <strong>Rp {{ number_format($reservation->order->total * 0.93, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar Actions --}}
        <div class="col-lg-4">
            {{-- Status Management --}}
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-gear"></i> Kelola Status</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.reservations.updateStatus', $reservation->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Ubah Status</label>
                            <select name="status" class="form-select" required>
                                @foreach(\App\Models\Reservation::getAllStatuses() as $key => $label)
                                    <option value="{{ $key }}" {{ $reservation->status == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nomor Meja</label>
                            <input type="text" 
                                   name="table_number" 
                                   class="form-control" 
                                   value="{{ $reservation->table_number }}"
                                   placeholder="Contoh: A1, B3, VIP-1">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning"></i> Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($reservation->status == 'pending')
                            <form action="{{ route('admin.reservations.confirm', $reservation->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check-circle"></i> Konfirmasi Reservasi
                                </button>
                            </form>
                        @endif

                        @if(in_array($reservation->status, ['confirmed', 'preparing', 'ready']))
                            <form action="{{ route('admin.reservations.markArrived', $reservation->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-info w-100">
                                    <i class="bi bi-person-check"></i> Customer Datang
                                </button>
                            </form>
                        @endif

                        @if($reservation->status == 'customer_arrived')
                            <form action="{{ route('admin.reservations.complete', $reservation->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-secondary w-100">
                                    <i class="bi bi-check-all"></i> Selesaikan Reservasi
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection