# TODO

## Marketplace
- [x] Pastikan route checkout: `marketplace.checkout` menerima input `payment_proof` & `shipping_address` sesuai form.
- [ ] Perbaiki issue checkout (jika gagal/redirect error) pada controller/route/view.


## Review - bintang
- [x] Ubah tampilan rating di `resources/views/reviews/create.blade.php`: tampilkan bintang kosong abu-abu (tanpa warna).
- [x] Biar bintang baru menyala (warna kuning) saat user klik.
- [ ] Pastikan data rating tersimpan benar: controller validasi `rating` 1-5, dan view mengirim `rating`.
- [ ] Cek juga tampilan review bintang di halaman lain (mis. list/ detail) bila ada.


