@extends('layouts.app')

@section('content')
    <div class="py-2" style="max-width: 800px; margin: 0 auto;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1" style="font-family: 'Fraunces', serif; font-weight: 700; color: var(--ink);">Edit Produk 🛍️</h1>
                <div class="small text-muted" style="font-family: 'Outfit', sans-serif;">Perbarui data, stok, dan harga produk marketplace.</div>
            </div>
            <div>
                <a class="pet-btn pet-btn-outline" href="{{ route('admin.products.index') }}">← Kembali</a>
            </div>
        </div>

        <div class="pet-card p-4 mb-4">
            <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold" style="color: var(--ink);">Nama Produk</label>
                        <input class="form-control pet-input w-100" type="text" name="name" value="{{ old('name', $product->name) }}" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold" style="color: var(--ink);">Harga (Rp)</label>
                        <input class="form-control pet-input w-100" type="number" name="price" value="{{ old('price', $product->price) }}" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold" style="color: var(--ink);">Stok</label>
                        <input class="form-control pet-input w-100" type="number" name="stock" value="{{ old('stock', $product->stock) }}" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold" style="color: var(--ink);">Gambar Baru (opsional)</label>
                        <input class="form-control pet-input w-100" type="file" name="image" accept="image/*">
                    </div>

                    @if($product->image)
                        <div class="col-12">
                            <label class="form-label d-block fw-semibold" style="color: var(--ink);">Gambar Saat Ini</label>
                            <div class="nb-photo-mask bg-light" style="max-width: 200px; height: 120px;">
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </div>
                    @endif

                    <div class="col-12">
                        <label class="form-label fw-semibold" style="color: var(--ink);">Deskripsi Produk</label>
                        <textarea class="form-control pet-input w-100" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button class="pet-btn pet-btn-primary flex-grow-1 py-3" type="submit">Perbarui Produk ✅</button>
            </form>

            <form method="POST" action="{{ route('products.destroy', $product) }}" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="pet-btn nb-btn-danger py-3 px-4">Hapus 🗑️</button>
            </form>
        </div>
    </div>
@endsection
