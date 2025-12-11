@extends('layouts.admin')

@section('title', 'Kelola Reservasi')

@section('content')
<div class="w-full px-4 py-6">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold flex items-center gap-2">
            <i class="bi bi-calendar-check"></i> Kelola Reservasi
        </h2>
    </div>

    {{-- Success Alert --}}
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-500/15 border border-green-600 text-green-700 px-4 py-3 flex justify-between">
            <span>{{ session('success') }}</span>
            <button class="text-green-700" onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    {{-- Error Alert --}}
    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-500/15 border border-red-600 text-red-700 px-4 py-3 flex justify-between">
            <span>{{ session('error') }}</span>
            <button class="text-red-700" onclick="this.parentElement.remove()">✕</button>
        </div>
    @endif

    {{-- Filter Card --}}
    <div class="bg-white shadow rounded-xl p-6 mb-6">
        <form method="GET" action="{{ route('admin.reservations.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg p-2.5">
                    <option value="">Semua Status</option>
                    @foreach(\App\Models\Reservation::getAllStatuses() as $key => $label)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Restaurant --}}
            <div>
                <label class="block text-sm font-medium mb-1">Restoran</label>
                <select name="restaurant_id" class="w-full border border-gray-300 rounded-lg p-2.5">
                    <option value="">Semua Restoran</option>
                    @foreach($restaurants as $restaurant)
                        <option value="{{ $restaurant->id }}"
                            {{ request('restaurant_id') == $restaurant->id ? 'selected' : '' }}>
                            {{ $restaurant->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Date --}}
            <div>
                <label class="block text-sm font-medium mb-1">Tanggal</label>
                <input type="date" name="date"
                       class="w-full border border-gray-300 rounded-lg p-2.5"
                       value="{{ request('date') }}">
            </div>

            {{-- Buttons --}}
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2">
                    <i class="bi bi-search"></i> Filter
                </button>

                <a href="{{ route('admin.reservations.index') }}"
                   class="px-4 py-2 border border-gray-400 text-gray-700 rounded-lg hover:bg-gray-100 flex items-center gap-2">
                    <i class="bi bi-x"></i> Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Reservations Table --}}
    <div class="bg-white shadow rounded-xl p-6">
        @if($reservations->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="px-4 py-3 border-b">ID</th>
                            <th class="px-4 py-3 border-b">Customer</th>
                            <th class="px-4 py-3 border-b">Restoran</th>
                            <th class="px-4 py-3 border-b">Tanggal & Waktu</th>
                            <th class="px-4 py-3 border-b">Tamu</th>
                            <th class="px-4 py-3 border-b">Meja</th>
                            <th class="px-4 py-3 border-b">Status</th>
                            <th class="px-4 py-3 border-b">Total</th>
                            <th class="px-4 py-3 border-b">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($reservations as $reservation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 border-b">#{{ $reservation->id }}</td>

                                <td class="px-4 py-3 border-b">
                                    <div class="font-semibold">{{ $reservation->customer->full_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $reservation->customer->email }}</div>
                                </td>

                                <td class="px-4 py-3 border-b">{{ $reservation->restaurant->name }}</td>

                                <td class="px-4 py-3 border-b">
                                    {{ $reservation->reservation_date->translatedFormat('d M Y') }}
                                    <div class="text-sm text-gray-500">
                                        {{ $reservation->reservation_date->format('H:i') }}
                                    </div>
                                </td>

                                <td class="px-4 py-3 border-b">{{ $reservation->guest_count }} org</td>

                                <td class="px-4 py-3 border-b">
                                    @if($reservation->table_number)
                                        <span class="px-2 py-1 rounded bg-blue-100 text-blue-700 text-xs font-medium">
                                            {{ $reservation->table_number }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 border-b">{!! \App\Models\Reservation::getStatusBadge($reservation->status) !!}</td>

                                <td class="px-4 py-3 border-b">
                                    @if($reservation->order)
                                        {{ $reservation->order->formatted_total }}
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="px-4 py-3 border-b">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.reservations.show', $reservation->id) }}"
                                           class="p-2 border border-blue-500 text-blue-600 rounded hover:bg-blue-50"
                                           title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if($reservation->status == 'pending')
                                            <form action="{{ route('admin.reservations.confirm', $reservation->id) }}"
                                                  method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="p-2 border border-green-600 text-green-600 rounded hover:bg-green-50"
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
            <div class="mt-6 flex justify-center">
                {{ $reservations->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-10 text-gray-700">
                <i class="bi bi-info-circle text-4xl mb-3 block"></i>
                <h5 class="text-xl font-medium">Tidak ada data reservasi</h5>
                <p class="text-gray-500">Belum ada reservasi sesuai filter yang dipilih.</p>
            </div>
        @endif
    </div>
</div>
@endsection
