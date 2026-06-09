@extends('layouts.dashboard')

@section('dashboard_content')
    <div class="py-2">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1" style="font-family: 'Fraunces', serif; font-weight: 700; color: var(--ink);">Keranjang Belanja 🛒</h1>
                <div class="small text-muted" style="font-family: 'Outfit', sans-serif;">Ringkasan belanjaan dan proses checkout pesanan Anda.</div>
            </div>
            <div class="d-flex gap-2">
                <a class="pet-btn pet-btn-outline" href="{{ route('marketplace.index') }}">⬅️ Kembali Belanja</a>
            </div>
        </div>

        @php
            $items = is_array($cart) ? $cart : [];
            $total = 0;
            foreach($items as $id => $it){ $total += $it['price'] * $it['quantity']; }
        @endphp

        @if(empty($items))
            <div class="pet-card p-5 text-center text-muted">
                <span class="fs-1 d-block mb-3">🐾</span>
                Keranjang belanja Anda masih kosong.
            </div>
        @else
            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="pet-card p-4">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h2 class="h5 mb-0" style="font-family: 'Fraunces', serif; font-weight: 700;">🛍️ Item Terpilih</h2>
                            <span class="pet-badge pet-badge-sage">{{ count($items) }} Produk</span>
                        </div>

                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th class="text-center">Jumlah</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $id => $it)
                                        <tr>
                                            <td>
                                                <div class="fw-bold" style="color: var(--ink);">{{ $it['name'] }}</div>
                                            </td>
                                            <td class="text-center fw-semibold">{{ $it['quantity'] }}</td>
                                            <td class="small">Rp {{ number_format((float)$it['price'], 0, ',', '.') }}</td>
                                            <td class="fw-semibold" style="color: var(--primary);">Rp {{ number_format((float)($it['price']*$it['quantity']), 0, ',', '.') }}</td>
                                            <td class="text-end">
                                                <form method="POST" action="{{ route('marketplace.cart.remove', $id) }}" class="m-0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="pet-btn pet-btn-outline py-1 px-3" style="font-size: 0.8rem;" type="submit">Hapus 🗑️</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-4 border-top pt-3">
                            <h4 class="h5 mb-0" style="font-family: 'Fraunces', serif; font-weight: 700;">Total Pembelanjaan:</h4>
                            <span class="fs-4 fw-bold" style="color: var(--primary);">Rp {{ number_format((float)$total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="pet-card p-4">
                        <h2 class="h5 mb-3" style="font-family: 'Fraunces', serif; font-weight: 700;">🚚 Alamat Pengiriman</h2>
                        
                        <form method="POST" action="{{ route('marketplace.checkout') }}">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-semibold small text-muted">Alamat Pengiriman Lengkap</label>
                                <textarea class="form-control pet-input w-100" name="shipping_address" rows="3" required placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, dan kode pos..."></textarea>
                            </div>
                            <button class="pet-btn pet-btn-primary w-100 py-3" type="submit">Bayar & Buat Order 💳</button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection



