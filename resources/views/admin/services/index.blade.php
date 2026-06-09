@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="py-2">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-4 mb-4">
            <div>
                <h1 class="h3 mb-1" style="font-family: 'Fraunces', serif; font-weight: 700; color: var(--ink);">📅 Manajemen Layanan & Jadwal</h1>
                <div class="small text-muted" style="font-family: 'Outfit', sans-serif; max-width: 500px;">Kelola layanan yang tersedia (Grooming, Vet, Pet Sitter) dan atur jadwal ketersediaan provider.</div>
            </div>
            <div class="d-flex gap-2">
                <a class="pet-btn pet-btn-primary" href="{{ route('services.create') }}">+ Tambah Layanan</a>
                <a class="pet-btn pet-btn-outline" href="{{ route('admin.dashboard') }}">← Dashboard</a>
            </div>
        </div>

        <div class="row g-4">
            {{-- Services List --}}
            <div class="col-12">
                <div class="pet-card p-4">
                    <div class="mb-3">
                        <h2 class="h5 mb-0" style="font-family: 'Fraunces', serif; font-weight: 700;">📋 Daftar Layanan</h2>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Nama Layanan</th>
                                    <th>Tipe</th>
                                    <th>Provider</th>
                                    <th>Deskripsi</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($services as $service)
                                    <tr>
                                        <td><strong style="color: var(--ink);">{{ $service->name }}</strong></td>
                                        <td><span class="pet-badge pet-badge-peach">{{ ucfirst($service->type) }}</span></td>
                                        <td>{{ $service->provider->name ?? '-' }}</td>
                                        <td class="small text-muted">{{ Str::limit($service->description, 50) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('services.edit', $service) }}" class="text-decoration-none fw-bold" style="color: var(--primary); font-size: 0.9rem;">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted small py-4">Belum ada layanan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Provider Schedules --}}
            <div class="col-12" id="schedules">
                <div class="pet-card p-4">
                    <div class="mb-3">
                        <h2 class="h5 mb-0" style="font-family: 'Fraunces', serif; font-weight: 700;">⏰ Jadwal Provider & Slot Booking</h2>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Provider</th>
                                    <th>Hari</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Selesai</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $schedule)
                                    <tr>
                                        <td><strong style="color: var(--ink);">{{ $schedule->provider->name }}</strong></td>
                                        <td>{{ $schedule->day_of_week }}</td>
                                        <td>{{ $schedule->start_time }}</td>
                                        <td>{{ $schedule->end_time }}</td>
                                        <td>
                                            @if($schedule->is_available)
                                                <span class="pet-badge pet-badge-sage">Tersedia</span>
                                            @else
                                                <span class="pet-badge">Tidak Tersedia</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted small py-4">Belum ada jadwal</td>
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
