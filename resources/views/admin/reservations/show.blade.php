@extends('layouts.admin')

@section('title', 'Detail Reservasi #' . $reservation->id)

@section('content')
<div class="w-full p-6">

    {{-- Back Button --}}
    <div class="mb-4">
        <a href="{{ route('admin.reservations.index') }}"
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
            ← Kembali
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-600 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-600 text-red-800 rounded">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- MAIN CONTENT --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Reservation Info Card --}}
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="bg-orange-600 text-white p-4">
                    <h3 class="text-lg font-semibold">Reservasi #{{ $reservation->id }}</h3>
                </div>

                <div class="p-6 space-y-6">

                    {{-- Customer & Restaurant --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs text-gray-500">Customer</p>
                            <p class="text-lg font-semibold">{{ $reservation->customer->full_name }}</p>
                            <p class="text-gray-500">{{ $reservation->customer->email }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Restoran</p>
                            <p class="text-lg font-semibold">{{ $reservation->restaurant->name }}</p>
                        </div>
                    </div>

                    <hr>

                    {{-- Date & Guest --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="font-medium"><span class="text-blue-600">📅</span> Tanggal & Waktu</p>
                            <p>{{ $reservation->formatted_date }}</p>
                        </div>

                        <div>
                            <p class="font-medium"><span class="text-blue-600">👥</span> Jumlah Tamu</p>
                            <p>{{ $reservation->guest_count }} orang</p>
                        </div>
                    </div>

                    {{-- Table & Status --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="font-medium"><span class="text-blue-600">🪑</span> Nomor Meja</p>
                            @if($reservation->table_number)
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-sm">
                                    {{ $reservation->table_number }}
                                </span>
                            @else
                                <span class="text-gray-500">Belum ditentukan</span>
                            @endif
                        </div>

                        <div>
                            <p class="font-medium"><span class="text-blue-600">🏷️</span> Status</p>
                            <div>{!! \App\Models\Reservation::getStatusBadge($reservation->status) !!}</div>
                        </div>
                    </div>

                    {{-- Special Request --}}
                    @if($reservation->special_request)
                        <hr>
                        <div>
                            <p class="font-medium"><span class="text-blue-600">💬</span> Permintaan Khusus</p>
                            <p class="mt-2 p-3 bg-gray-100 rounded">{{ $reservation->special_request }}</p>
                        </div>
                    @endif

                    <hr>

                    <p class="text-sm text-gray-500">
                        ⏱️ Dibuat: {{ $reservation->created_at->translatedFormat('d F Y, H:i') }}
                    </p>
                </div>
            </div>

            {{-- Pre-Order Items --}}
            @if($reservation->order && $reservation->order->orderItems->count() > 0)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="bg-orange-600 text-white p-4">
                        <h3 class="text-lg font-semibold">Pre-Order Menu</h3>
                    </div>

                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-100 text-sm text-gray-600">
                                        <th class="p-3">Menu</th>
                                        <th class="p-3 text-center">Qty</th>
                                        <th class="p-3 text-right">Harga</th>
                                        <th class="p-3 text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservation->order->orderItems as $item)
                                        <tr class="border-b">
                                            <td class="p-3">{{ $item->menu_name }}</td>
                                            <td class="p-3 text-center">{{ $item->quantity }}</td>
                                            <td class="p-3 text-right">{{ $item->formatted_total }}</td>
                                            <td class="p-3 text-right font-semibold">
                                                Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="3" class="p-3 text-right font-medium">Total:</td>
                                        <td class="p-3 text-right text-blue-600 font-bold">
                                            {{ $reservation->order->formatted_total }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="p-3 text-right text-gray-500">Komisi (7%):</td>
                                        <td class="p-3 text-right text-gray-500">
                                            Rp {{ number_format($reservation->order->total * 0.07, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="p-3 text-right text-gray-500">Diterima Restoran:</td>
                                        <td class="p-3 text-right text-green-600 font-semibold">
                                            Rp {{ number_format($reservation->order->total * 0.93, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- SIDEBAR ACTIONS --}}
        <div class="space-y-6">

            {{-- Status Update Card --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Kelola Status</h4>

                <form action="{{ route('admin.reservations.updateStatus', $reservation->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Ubah Status</label>
                        <select name="status"
                                class="mt-1 w-full border-gray-300 rounded-lg p-2 focus:ring-blue-500">
                            @foreach(\App\Models\Reservation::getAllStatuses() as $key => $label)
                                <option value="{{ $key }}" {{ $reservation->status == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium">Nomor Meja</label>
                        <input type="text"
                               name="table_number"
                               value="{{ $reservation->table_number }}"
                               class="mt-1 w-full border-gray-300 rounded-lg p-2"
                               placeholder="Contoh: A1, B3, VIP-1">
                    </div>

                    <button class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600">
                        Update Status
                    </button>
                </form>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h4 class="text-lg font-semibold mb-4">Aksi Cepat</h4>

                <div class="space-y-3">

                    @if($reservation->status == 'pending')
                        <form action="{{ route('admin.reservations.confirm', $reservation->id) }}" method="POST">
                            @csrf
                            <button class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600">
                                Konfirmasi Reservasi
                            </button>
                        </form>
                    @endif

                    @if(in_array($reservation->status, ['confirmed', 'preparing', 'ready']))
                        <form action="{{ route('admin.reservations.markArrived', $reservation->id) }}" method="POST">
                            @csrf
                            <button class="w-full bg-orange-500 text-white py-2 rounded-lg hover:bg-orange-600">
                                Customer Datang
                            </button>
                        </form>
                    @endif

                    @if($reservation->status == 'customer_arrived')
                        <form action="{{ route('admin.reservations.complete', $reservation->id) }}" method="POST">
                            @csrf
                            <button class="w-full bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700">
                                Selesaikan Reservasi
                            </button>
                        </form>
                    @endif

                </div>
            </div>

        </div>

    </div>
</div>
@endsection
