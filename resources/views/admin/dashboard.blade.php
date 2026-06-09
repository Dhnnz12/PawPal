@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="py-2">
        {{-- Header --}}
        <div class="d-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
            <div>
                <h1 class="h3 mb-1" style="font-family: 'Fraunces', serif; font-weight: 800; color: #4e3629;">Dashboard Admin 📊</h1>
                <div class="small text-muted">Selamat datang! Kelola semua data, transaksi, dan verifikasi sistem PawPal.</div>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="fs-2">⚙️</span>
                <div>
                    <div class="fw-bold small text-dark">Administrator</div>
                    <div class="small text-muted">Online</div>
                </div>
            </div>
        </div>

        {{-- Statistics Row --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="summary-widget">
                    <div>
                        <div class="value">{{ $totalUsers }}</div>
                        <div class="small text-muted fw-semibold">Total Pengguna</div>
                    </div>
                    <div class="fs-2">👥</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-widget" style="border-left: 4.5px solid #d48c6a;">
                    <div>
                        <div class="value">{{ $totalBookings }}</div>
                        <div class="small text-muted fw-semibold">Total Pemesanan</div>
                    </div>
                    <div class="fs-2">📅</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-widget" style="border-left: 4.5px solid #b06948;">
                    <div>
                        <div class="value" style="font-size: 1.3rem; margin-top: 15px;">Rp {{ number_format($totalTransactions, 0, ',', '.') }}</div>
                        <div class="small text-muted fw-semibold mt-1">Pendapatan</div>
                    </div>
                    <div class="fs-2">💵</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="summary-widget" style="border-left: 4.5px solid #759a83;">
                    <div>
                        <div class="value">{{ $allBookings->whereIn('status', ['pending', 'confirmed'])->count() }}</div>
                        <div class="small text-muted fw-semibold">Pemesanan Aktif</div>
                    </div>
                    <div class="fs-2">⏳</div>
                </div>
            </div>
        </div>

        {{-- Verification & Latest Bookings --}}
        <div class="row g-4">
            {{-- Pending Provider Verification --}}
            <div class="col-12 col-lg-5">
                <div class="pet-card p-4 bg-white h-100">
                    <h3 class="h5 mb-3" style="font-family: 'Fraunces', serif; font-weight: 700; color: #4e3629;">⏳ Verifikasi Provider Baru</h3>
                    <div class="list-group list-group-flush">
                        @forelse($pendingProviders as $p)
                            <div class="list-group-item bg-transparent px-0 py-3 border-0 border-bottom d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fw-bold text-dark">{{ $p->name }}</div>
                                    <div class="small text-muted">{{ ucfirst($p->provider_type ?? 'Provider') }}</div>
                                </div>
                                <div class="d-flex gap-2">
                                    <form method="POST" action="{{ route('admin.verifyProvider', $p) }}" class="m-0">
                                        @csrf
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn btn-sm text-white py-1 px-3" style="background-color: var(--accent); border: none; border-radius: 8px;">Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.verifyProvider', $p) }}" class="m-0">
                                        @csrf
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="btn btn-sm text-white py-1 px-3" style="background-color: #c06c48; border: none; border-radius: 8px;">Tolak</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-5 small">
                                Tidak ada provider baru menunggu verifikasi.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Pemesanan Terbaru Table --}}
            <div class="col-12 col-lg-7">
                <div class="pet-card p-4 bg-white h-100">
                    <h3 class="h5 mb-3" style="font-family: 'Fraunces', serif; font-weight: 700; color: #4e3629;">📅 Pemesanan Terbaru</h3>
                    
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Pelanggan</th>
                                    <th>Layanan</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allBookings->take(5) as $b)
                                    <tr>
                                        <td><strong>#{{ $b->id }}</strong></td>
                                        <td>{{ $b->petOwner->name ?? '-' }}</td>
                                        <td>{{ $b->service->name ?? '-' }}</td>
                                        <td class="small text-muted">{{ $b->booking_date }}</td>
                                        <td>
                                            @if($b->status === 'completed')
                                                <span class="pet-badge pet-badge-sage">Selesai</span>
                                            @elseif($b->status === 'pending')
                                                <span class="pet-badge">Pending</span>
                                            @else
                                                <span class="pet-badge" style="background-color: #fcece6; color: #c06c48;">{{ ucfirst($b->status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4 small">Belum ada pemesanan masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
