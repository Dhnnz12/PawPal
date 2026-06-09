@extends('layouts.app')

@section('content')
    <div class="py-2">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1" style="font-family: 'Fraunces', serif; font-weight: 700; color: var(--ink);">Dashboard Service Provider 🧑‍⚕️</h1>
                <div class="small text-muted" style="font-family: 'Outfit', sans-serif;">Kelola ketersediaan jadwal, pantau booking masuk, dan kelola produk marketplace Anda.</div>
            </div>
            <div class="d-flex gap-2">
                <span class="pet-badge pet-badge-sage">🌿 Aktif</span>
            </div>
        </div>

        <div class="row g-4">
            {{-- Schedules --}}
            <div class="col-12 col-lg-5">
                <div class="pet-card p-4 h-100">
                    <div class="mb-3">
                        <h2 class="h5 mb-0" style="font-family: 'Fraunces', serif; font-weight: 700;">📅 Ketersediaan Jadwal</h2>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Waktu Operasional</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $s)
                                    <tr>
                                        <td class="fw-bold" style="color: var(--ink);">{{ $s->day_of_week }}</td>
                                        <td class="small">{{ $s->start_time }} - {{ $s->end_time }}</td>
                                        <td>
                                            @if($s->is_available)
                                                <span class="pet-badge pet-badge-sage">Buka</span>
                                            @else
                                                <span class="pet-badge" style="background-color: #fcece6; color: #c06c48;">Tutup</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-muted small py-3 text-center">Belum ada jadwal operasional.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Bookings --}}
            <div class="col-12 col-lg-7">
                <div class="pet-card p-4 h-100">
                    <div class="mb-3">
                        <h2 class="h5 mb-0" style="font-family: 'Fraunces', serif; font-weight: 700;">📋 Pesanan & Booking Masuk</h2>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Waktu & Layanan</th>
                                    <th>Klien & Hewan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $b)
                                    <tr>
                                        <td>
                                            <div class="fw-bold" style="color: var(--ink);">{{ $b->booking_date }}</div>
                                            <div class="small text-muted mb-1">{{ $b->start_time }}</div>
                                            <span class="pet-badge" style="font-size: 0.75rem; padding: 3px 8px;">{{ $b->service->name ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $b->petOwner->name ?? '-' }}</div>
                                            <div class="small text-muted">Hewan: <span class="fw-bold text-dark">{{ $b->pet->name ?? '-' }}</span></div>
                                        </td>
                                        <td>
                                            @if($b->status === 'completed')
                                                <span class="pet-badge pet-badge-sage">Completed</span>
                                            @elseif($b->status === 'pending')
                                                <span class="pet-badge">Pending</span>
                                            @else
                                                <span class="pet-badge" style="background-color: #fcece6; color: #c06c48;">{{ ucfirst($b->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-muted small py-3 text-center">Belum ada booking masuk.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Marketplace Products --}}
            <div class="col-12">
                <div class="pet-card p-4">
                    <div class="mb-3">
                        <h2 class="h5 mb-0" style="font-family: 'Fraunces', serif; font-weight: 700;">🛍️ Katalog Produk Anda (Marketplace)</h2>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Harga Jual</th>
                                    <th>Stok Tersedia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $p)
                                    <tr>
                                        <td class="fw-bold" style="color: var(--ink);">{{ $p->name }}</td>
                                        <td class="fw-semibold" style="color: var(--primary);">
                                            Rp {{ number_format($p->price, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($p->stock > 0)
                                                <span class="pet-badge pet-badge-sage">{{ $p->stock }} Pcs</span>
                                            @else
                                                <span class="pet-badge" style="background-color: #fcece6; color: #c06c48;">Habis</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-muted small py-3 text-center">Belum ada katalog produk terdaftar.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


