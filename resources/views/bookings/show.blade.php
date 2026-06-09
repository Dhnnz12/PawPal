@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="py-2" style="max-width: 700px; margin: 0 auto;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1" style="font-family: 'Fraunces', serif; font-weight: 700; color: var(--ink);">🗓️ Detail Pemesanan Layanan</h1>
                <div class="small text-muted" style="font-family: 'Outfit', sans-serif;">Informasi lengkap pemesanan layanan Anda</div>
            </div>
            <div>
                <a class="pet-btn pet-btn-outline" href="{{ route('bookings.index') }}">← Kembali</a>
            </div>
        </div>

        <div class="pet-card p-4 mb-4">
            <div class="d-flex align-items-start justify-content-between mb-4">
                <div>
                    <h3 class="h5 mb-2" style="font-family: 'Fraunces', serif; font-weight: 700; color: var(--ink);">{{ $booking->service->name }}</h3>
                    <p class="small text-muted mb-0">Booking ID: #{{ $booking->id }}</p>
                </div>
                @if($booking->status === 'completed')
                    <span class="pet-badge pet-badge-sage">✓ Selesai</span>
                @elseif($booking->status === 'scheduled')
                    <span class="pet-badge pet-badge-peach">📅 Terjadwal</span>
                @elseif($booking->status === 'pending')
                    <span class="pet-badge">⏳ Pending</span>
                @elseif($booking->status === 'cancelled')
                    <span class="pet-badge" style="background-color: #fcece6; color: #c06c48;">❌ Dibatalkan</span>
                @else
                    <span class="pet-badge">{{ ucfirst($booking->status) }}</span>
                @endif
            </div>

            <hr style="border-color: var(--color-warm-border);">

            {{-- Booking Details Grid --}}
            <div class="row g-4 mb-4">
                <div class="col-6">
                    <label class="small fw-semibold text-muted mb-2 d-block">Tanggal Kunjungan</label>
                    <p class="h6 mb-0" style="color: var(--ink);">📅 {{ $booking->booking_date }}</p>
                </div>
                <div class="col-6">
                    <label class="small fw-semibold text-muted mb-2 d-block">Jam Kunjungan</label>
                    <p class="h6 mb-0" style="color: var(--ink);">🕐 {{ $booking->start_time }} - {{ $booking->end_time }}</p>
                </div>
                <div class="col-6">
                    <label class="small fw-semibold text-muted mb-2 d-block">Provider Layanan</label>
                    <p class="h6 mb-0" style="color: var(--ink);">{{ $booking->provider->name }}</p>
                    <p class="small text-muted mb-0">{{ ucfirst($booking->provider->provider_type ?? '') }}</p>
                </div>
                <div class="col-6">
                    <label class="small fw-semibold text-muted mb-2 d-block">Hewan Peliharaan</label>
                    <p class="h6 mb-0" style="color: var(--ink);">🐾 {{ $booking->pet->name }}</p>
                    <p class="small text-muted mb-0">{{ $booking->pet->type }} • {{ $booking->pet->age }} tahun</p>
                </div>
            </div>

            {{-- Alamat Lokasi --}}
            <hr style="border-color: var(--color-warm-border);">
            
            <div class="mb-4">
                <label class="small fw-semibold text-muted mb-2 d-block">📍 Lokasi Layanan</label>
                <div class="bg-light p-3 rounded" style="border-radius: 15px;">
                    <p class="mb-1" style="color: var(--ink); font-weight: 600;">{{ $booking->address->label ?? 'Lokasi' }}</p>
                    <p class="small text-muted mb-0">{{ $booking->address->address_line ?? '-' }}</p>
                    @if($booking->address && $booking->address->latitude && $booking->address->longitude)
                        <div class="mt-2 small text-muted">
                            📌 Koordinat: {{ $booking->address->latitude }}, {{ $booking->address->longitude }}
                        </div>
                    @endif
                </div>
            </div>

            {{-- Catatan --}}
            @if($booking->notes)
                <div class="mb-4">
                    <label class="small fw-semibold text-muted mb-2 d-block">📝 Catatan Tambahan</label>
                    <p style="color: var(--ink);">{{ $booking->notes }}</p>
                </div>
            @endif

            {{-- Provider Info Card --}}
            <hr style="border-color: var(--color-warm-border);">
            
            <div class="mb-4">
                <label class="small fw-semibold text-muted mb-3 d-block">Informasi Provider</label>
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="h6 mb-1" style="color: var(--ink);">{{ $booking->provider->name }}</p>
                        <p class="small text-muted mb-1">📞 {{ $booking->provider->phone ?? 'N/A' }}</p>
                        <p class="small text-muted mb-0">{{ $booking->provider->bio ?? 'Professional service provider' }}</p>
                    </div>
                    <div>
                        @if($booking->provider->is_verified)
                            <span class="pet-badge pet-badge-sage">✓ Terverifikasi</span>
                        @else
                            <span class="pet-badge">Belum Verifikasi</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <hr style="border-color: var(--color-warm-border);">
            
            <div class="d-flex gap-2 mt-4">
                @if($booking->status === 'completed' && !$booking->reviewed_at)
                    <a href="{{ route('reviews.create', $booking) }}" class="pet-btn pet-btn-primary flex-grow-1 py-2">⭐ Beri Rating & Review</a>
                @endif
                
                @if($booking->status === 'pending' || $booking->status === 'scheduled')
                    <form method="POST" action="{{ route('booking.updateStatus', $booking) }}" class="flex-grow-1">
                        @csrf
                        <input type="hidden" name="status" value="cancelled">
                        <button type="submit" class="pet-btn pet-btn-outline w-100 py-2" onclick="return confirm('Yakin ingin membatalkan booking ini?')">Batalkan Pemesanan</button>
                    </form>
                @endif
                
                <a href="{{ route('bookings.index') }}" class="pet-btn pet-btn-outline flex-grow-1 py-2">← Kembali</a>
            </div>
        </div>
    </div>
@endsection
