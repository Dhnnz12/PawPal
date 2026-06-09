@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="py-2" style="max-width: 820px; margin: 0 auto;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1" style="font-family: 'Fraunces', serif; font-weight: 700; color: var(--ink);">Edit Profil Hewan 🐾</h1>
                <div class="small text-muted" style="font-family: 'Outfit', sans-serif;">Perbarui informasi hewan peliharaan Anda.</div>
            </div>
            <div class="d-flex gap-2">
                <a class="pet-btn pet-btn-outline" href="{{ route('pets.show', $pet) }}">⬅️ Kembali</a>
            </div>
        </div>

        <div class="pet-card p-4">
            <form method="POST" action="{{ route('pets.update', $pet) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold" style="color: var(--ink);">Nama Hewan</label>
                        <input class="form-control pet-input w-100" type="text" name="name" value="{{ old('name', $pet->name) }}" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold" style="color: var(--ink);">Jenis (Spesies)</label>
                        <input class="form-control pet-input w-100" type="text" name="type" value="{{ old('type', $pet->type) }}" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold" style="color: var(--ink);">Breed (opsional)</label>
                        <input class="form-control pet-input w-100" type="text" name="breed" value="{{ old('breed', $pet->breed) }}">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold" style="color: var(--ink);">Usia (tahun)</label>
                        <input class="form-control pet-input w-100" type="number" name="age" min="0" value="{{ old('age', $pet->age) }}" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold" style="color: var(--ink);">Berat (opsional)</label>
                        <input class="form-control pet-input w-100" type="number" name="weight" min="0" step="0.01" value="{{ old('weight', $pet->weight) }}">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold" style="color: var(--ink);">Foto (opsional)</label>
                        <input class="form-control pet-input w-100" type="file" name="photo" accept="image/*">
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold" style="color: var(--ink);">Catatan Kesehatan</label>
                        <textarea class="form-control pet-input w-100" name="health_notes" rows="3">{{ old('health_notes', $pet->health_notes) }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button class="pet-btn pet-btn-primary w-100 py-3" type="submit">Simpan Perubahan ✅</button>
                </div>
            </form>
        </div>
    </div>
@endsection

