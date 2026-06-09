# 🖼️ Panduan Gambar & Storage - PET CARE

## ✅ Status Gambar

Semua gambar placeholder telah dibuat dan siap digunakan dalam aplikasi:

### 📸 Gambar yang Telah Dibuat

#### 1. **Avatar Pengguna** (5 files)
- `public/storage/avatars/owner-avatar.jpg` - Avatar Budi Santoso (Pet Owner)
- `public/storage/avatars/groomer-indra.jpg` - Avatar Indra (Groomer)
- `public/storage/avatars/vet-sarah.jpg` - Avatar Drh. Sarah Wijaya (Veterinarian)
- `public/storage/avatars/petshop-lestari.jpg` - Avatar Pet Shop Lestari (Seller)
- `public/storage/avatars/vet-toni.jpg` - Avatar Drh. Toni Hartono (Unverified Vet)

#### 2. **Foto Hewan Peliharaan** (2 files)
- `public/storage/pets/luna-persia-cat.jpg` - Luna (Kucing Persia)
- `public/storage/pets/rocky-golden-retriever.jpg` - Rocky (Golden Retriever)

#### 3. **Gambar Produk** (3 files)
- `public/storage/products/royal-canin-cat-food-2kg.jpg` - Makanan Kucing Royal Canin
- `public/storage/products/furrymagic-shampoo-250ml.jpg` - Shampoo FurryMagic
- `public/storage/products/cozynest-cat-bed.jpg` - Tempat Tidur CozyNest

#### 4. **Sertifikasi Dokter Hewan** (2 files)
- `public/storage/certifications/sertifikat-drh-sarah-wijaya.pdf` - Sertifikat Drh. Sarah
- `public/storage/certifications/sertifikat-drh-toni-hartono.pdf` - Sertifikat Drh. Toni

---

## 🗂️ Struktur Direktori Storage

```
public/
└── storage/
    ├── avatars/               # Avatar pengguna
    │   ├── owner-avatar.jpg
    │   ├── groomer-indra.jpg
    │   ├── vet-sarah.jpg
    │   ├── petshop-lestari.jpg
    │   └── vet-toni.jpg
    ├── pets/                  # Foto hewan peliharaan
    │   ├── luna-persia-cat.jpg
    │   └── rocky-golden-retriever.jpg
    ├── products/              # Gambar produk
    │   ├── royal-canin-cat-food-2kg.jpg
    │   ├── furrymagic-shampoo-250ml.jpg
    │   └── cozynest-cat-bed.jpg
    └── certifications/        # Dokumen sertifikasi
        ├── sertifikat-drh-sarah-wijaya.pdf
        └── sertifikat-drh-toni-hartono.pdf
```

---

## 🔗 Cara Mengakses Gambar di Views

### Dalam Blade Template

#### Menampilkan Avatar Pengguna
```blade
<img src="{{ asset('storage/' . $user->avatar) }}" 
     alt="{{ $user->name }}" 
     class="w-20 h-20 rounded-full">

<!-- Dengan fallback jika gambar tidak ada -->
<img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://via.placeholder.com/100?text=Avatar' }}" 
     alt="{{ $user->name }}" 
     class="w-20 h-20 rounded-full">
```

#### Menampilkan Foto Hewan Peliharaan
```blade
<img src="{{ asset('storage/' . $pet->photo) }}" 
     alt="{{ $pet->name }}" 
     class="w-full h-64 object-cover rounded-lg">
```

#### Menampilkan Gambar Produk
```blade
<img src="{{ asset('storage/' . $product->image) }}" 
     alt="{{ $product->name }}" 
     class="w-full h-48 object-cover rounded-lg">
```

#### Menampilkan Sertifikasi (Link Download)
```blade
@if($provider->certification)
    <a href="{{ asset('storage/' . $provider->certification) }}" 
       target="_blank" 
       class="text-blue-500 hover:underline">
        📄 Lihat Sertifikasi
    </a>
@endif
```

---

## 📤 Upload Gambar Baru (Dalam Controller)

### Untuk Pet Photo
```php
if ($request->hasFile('photo')) {
    $path = $request->file('photo')->store('pets', 'public');
    $pet->update(['photo' => $path]);
}
```

### Untuk Product Image
```php
if ($request->hasFile('image')) {
    $path = $request->file('image')->store('products', 'public');
    $product->update(['image' => $path]);
}
```

### Untuk User Avatar
```php
if ($request->hasFile('avatar')) {
    $path = $request->file('avatar')->store('avatars', 'public');
    $user->update(['avatar' => $path]);
}
```

### Untuk Certification
```php
if ($request->hasFile('certification')) {
    $path = $request->file('certification')->store('certifications', 'public');
    $user->update(['certification' => $path]);
}
```

---

## 🎨 Galeri Produk di Marketplace View

Contoh implementasi di `resources/views/marketplace/index.blade.php`:

```blade
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($products as $product)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <!-- Product Image -->
            <div class="h-48 bg-gray-200 overflow-hidden">
                <img src="{{ asset('storage/' . $product->image) }}" 
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover hover:scale-105 transition">
            </div>
            
            <!-- Product Info -->
            <div class="p-4">
                <h3 class="font-semibold text-lg">{{ $product->name }}</h3>
                <p class="text-gray-600 text-sm mt-2">{{ $product->description }}</p>
                
                <!-- Price & Stock -->
                <div class="flex justify-between items-center mt-4">
                    <span class="text-2xl font-bold text-green-600">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </span>
                    <span class="text-sm text-gray-500">
                        Stok: {{ $product->stock }}
                    </span>
                </div>
                
                <!-- Add to Cart -->
                <button class="w-full mt-4 bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">
                    🛒 Tambah ke Keranjang
                </button>
            </div>
        </div>
    @endforeach
</div>
```

---

## 👤 Profil Pengguna dengan Avatar

Contoh di `resources/views/owner/dashboard.blade.php`:

```blade
<div class="flex items-center gap-4">
    <!-- User Avatar -->
    <img src="{{ asset('storage/' . auth()->user()->avatar) }}" 
         alt="{{ auth()->user()->name }}"
         class="w-16 h-16 rounded-full border-2 border-blue-500">
    
    <!-- User Info -->
    <div>
        <h2 class="text-2xl font-bold">{{ auth()->user()->name }}</h2>
        <p class="text-gray-600">{{ auth()->user()->role_name }}</p>
        <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
    </div>
</div>

<!-- Pets Section -->
<section class="mt-8">
    <h3 class="text-xl font-bold mb-4">🐾 Hewan Peliharaan Saya</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @forelse(auth()->user()->pets as $pet)
            <div class="bg-white rounded-lg shadow-md p-4">
                <img src="{{ asset('storage/' . $pet->photo) }}" 
                     alt="{{ $pet->name }}"
                     class="w-full h-40 object-cover rounded-lg mb-3">
                
                <h4 class="text-lg font-semibold">{{ $pet->name }}</h4>
                <p class="text-gray-600">{{ $pet->type }} - {{ $pet->breed }}</p>
                <p class="text-sm text-gray-500">Usia: {{ $pet->age }} tahun</p>
            </div>
        @empty
            <p class="text-gray-500">Belum ada hewan peliharaan. Tambahkan sekarang!</p>
        @endforelse
    </div>
</section>
```

---

## 🎯 Service Provider Profile dengan Portfolio

Contoh di `resources/views/provider/profile.blade.php`:

```blade
<!-- Provider Avatar & Info -->
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex gap-6">
        <img src="{{ asset('storage/' . $provider->avatar) }}" 
             alt="{{ $provider->name }}"
             class="w-32 h-32 rounded-full border-4 border-blue-500">
        
        <div>
            <h2 class="text-2xl font-bold">{{ $provider->name }}</h2>
            <p class="text-green-600 font-semibold">{{ $provider->provider_type_label }}</p>
            
            @if($provider->is_verified)
                <span class="inline-block mt-2 bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                    ✅ Terverifikasi
                </span>
            @else
                <span class="inline-block mt-2 bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                    ⏳ Menunggu Verifikasi
                </span>
            @endif
            
            <p class="mt-3 text-gray-600">{{ $provider->bio }}</p>
        </div>
    </div>
</div>

<!-- Certification Badge untuk Veterinarian -->
@if($provider->isVet() && $provider->certification)
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mt-6">
        <p class="font-semibold text-blue-800">📜 Sertifikasi Terverifikasi</p>
        <a href="{{ asset('storage/' . $provider->certification) }}" 
           target="_blank"
           class="text-blue-500 hover:underline text-sm mt-2">
            Lihat Dokumen Sertifikasi
        </a>
    </div>
@endif
```

---

## 🔄 Reset Gambar Jika Diperlukan

Jika ingin reset gambar ke placeholder default lagi:

```bash
php artisan images:generate-placeholders
```

Command ini akan:
1. Membuat folder `public/storage/avatars`, `pets`, `products`, `certifications` jika belum ada
2. Download placeholder images dari service pihak ketiga
3. Jika download gagal, akan membuat placeholder PNG sederhana

---

## 📝 Catatan Penting

1. **Ukuran File**: Maksimal 2MB untuk image (kecuali product yang bisa lebih besar)
2. **Format**: JPG, PNG, GIF, WebP
3. **Akses**: Semua file di `public/storage/` dapat diakses via web
4. **Security**: Pastikan validasi file upload di controller
5. **Placeholder Service**: Menggunakan:
   - DiceBear untuk avatar (SVG)
   - Unsplash untuk foto hewan & produk (JPEG)
   - Via.placeholder.com untuk sertifikasi (placeholder)

---

## 🚀 Langkah Selanjutnya

1. **Upload Form** - Buat form upload gambar di profile edit
2. **Image Optimization** - Tambahkan image resizing & compression
3. **Gallery** - Buat gallery untuk portfolio service provider
4. **Lightbox** - Tambahkan lightbox untuk preview gambar besar
5. **CDN** - Deploy ke cloud storage (S3, Google Cloud Storage) untuk production

---

**Semua gambar sudah siap! Aplikasi Anda sekarang memiliki visual yang lengkap. 🎉**
