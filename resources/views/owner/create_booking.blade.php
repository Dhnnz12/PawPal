@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="py-2">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 border-bottom pb-3">
            <div>
                <h1 class="h3 mb-1" style="font-family: 'Fraunces', serif; font-weight: 800; color: #4e3629;">Booking Pemesanan Layanan 🐾</h1>
                <div class="small text-muted">Tentukan pilihan kategori layanan, tanggal, dan slot jam kunjungan.</div>
            </div>
            <a class="pet-btn pet-btn-outline" href="{{ route('booking.search') }}">← Kembali</a>
        </div>

        <form method="POST" action="{{ route('booking.store') }}">
            @csrf
            <input type="hidden" name="provider_id" value="{{ $provider->id }}">

            <div class="row g-4">
                {{-- Column 1: Service Selection --}}
                <div class="col-12 col-lg-3">
                    <div class="pet-card p-3 bg-white h-100">
                        <label class="fw-bold mb-3 d-block text-dark">📋 Pilih Layanan</label>
                        <div class="d-flex flex-column gap-2">
                            @foreach($services as $s)
                                <div class="p-3 rounded-3 border d-flex flex-column justify-content-between bg-light" style="cursor: pointer;">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="service_id" id="service_{{ $s->id }}" value="{{ $s->id }}" required checked>
                                        <label class="form-check-label fw-bold small" for="service_{{ $s->id }}">
                                            {{ $s->name }}
                                        </label>
                                    </div>
                                    <div class="small text-primary fw-bold mt-2">Rp {{ number_format($s->price, 0, ',', '.') }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Column 2: Date Picker (Calendar Grid Mockup) --}}
                <div class="col-12 col-lg-5">
                    <div class="pet-card p-3 bg-white h-100">
                        <label class="fw-bold mb-3 d-block text-dark">📅 Pilih Tanggal</label>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-bold small">Juni 2026</span>
                            <div>
                                <button type="button" class="btn btn-sm p-1 border-0">◀</button>
                                <button type="button" class="btn btn-sm p-1 border-0">▶</button>
                            </div>
                        </div>
                        
                        <div class="calendar-grid">
                            <div class="calendar-day-header">Sen</div>
                            <div class="calendar-day-header">Sel</div>
                            <div class="calendar-day-header">Rab</div>
                            <div class="calendar-day-header">Kam</div>
                            <div class="calendar-day-header">Jum</div>
                            <div class="calendar-day-header">Sab</div>
                            <div class="calendar-day-header">Min</div>
                            
                            {{-- Days Mockup --}}
                            @for($i = 1; $i <= 30; $i++)
                                <div class="calendar-day {{ $i === 7 ? 'active' : '' }}" onclick="selectDate(this, '2026-06-{{ sprintf('%02d', $i) }}')">
                                    {{ $i }}
                                </div>
                            @endfor
                        </div>
                        <input type="hidden" name="booking_date" id="selected_date_input" value="2026-06-07" required>
                    </div>
                </div>

                {{-- Column 3: Waktu, Hewan, Lokasi & Konfirmasi --}}
                <div class="col-12 col-lg-4">
                    <div class="pet-card p-3 bg-white h-100 d-flex flex-column justify-content-between">
                        <div>
                            {{-- Waktu Mulai --}}
                            <div class="mb-3">
                                <label class="fw-bold mb-2 d-block text-dark">⏰ Pilih Jam</label>
                                <div class="row g-2">
                                    @foreach(['09:00', '10:00', '11:00', '13:00', '14:00', '15:00'] as $time)
                                        <div class="col-4">
                                            <button type="button" class="time-slot-btn {{ $time === '10:00' ? 'active' : '' }}" onclick="selectTime(this, '{{ $time }}')">
                                                {{ $time }}
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <input type="hidden" name="start_time" id="selected_time_input" value="10:00" required>
                            </div>

                            {{-- Pilih Hewan --}}
                            <div class="mb-3">
                                <label class="fw-bold mb-2 d-block text-dark">🐾 Pilih Hewan</label>
                                <select class="form-select pet-input w-100" name="pet_id" required style="height: 50px;">
                                    @foreach($pets as $pet)
                                        <option value="{{ $pet->id }}">{{ $pet->name }} ({{ $pet->type }})</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Lokasi / Alamat --}}
                            <div class="mb-4">
                                <label class="fw-bold mb-2 d-block text-dark">📍 Lokasi Layanan</label>
                                <select class="form-select pet-input w-100" name="address_id" required style="height: 50px;">
                                    @foreach($addresses as $a)
                                        <option value="{{ $a->id }}">{{ $a->label }} — {{ $a->address_line }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="pet-btn pet-btn-primary w-100 py-3 mt-3">
                            Lanjutkan ➔
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function selectDate(element, dateStr) {
            document.querySelectorAll('.calendar-day').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            document.getElementById('selected_date_input').value = dateStr;
        }

        function selectTime(element, timeStr) {
            document.querySelectorAll('.time-slot-btn').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            document.getElementById('selected_time_input').value = timeStr;
        }
    </script>
@endsection
