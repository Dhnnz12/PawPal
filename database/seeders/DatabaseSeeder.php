<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pet;
use App\Models\Address;
use App\Models\Service;
use App\Models\Product;
use App\Models\ProviderSchedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@petcare.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_verified' => true,
        ]);

        // 2. Pet Owner
        $owner = User::create([
            'name' => 'Budi Santoso',
            'email' => 'owner@petcare.com',
            'password' => Hash::make('password'),
            'role' => 'pet_owner',
            'phone' => '081234567890',
            'bio' => 'Pecinta kucing dan anjing setia.',
            'avatar' => 'avatars/owner-avatar.svg',
            'is_verified' => true,
        ]);

        // Pets for Owner
        Pet::create([
            'user_id' => $owner->id,
            'name' => 'Luna',
            'type' => 'Cat',
            'breed' => 'Persia',
            'age' => 2,
            'weight' => 4.2,
            'photo' => 'pets/luna-persia-cat.svg',
            'health_notes' => 'Alergi makanan seafood.',
        ]);

        Pet::create([
            'user_id' => $owner->id,
            'name' => 'Rocky',
            'type' => 'Dog',
            'breed' => 'Golden Retriever',
            'age' => 3,
            'weight' => 28.5,
            'photo' => 'pets/rocky-golden-retriever.svg',
            'health_notes' => 'Rutin vaksin rabies tahunan.',
        ]);

        // Addresses for Owner
        Address::create([
            'user_id' => $owner->id,
            'label' => 'Rumah Utama',
            'address_line' => 'Jl. Kemang Raya No. 12, Mampang Prapatan',
            'city' => 'Jakarta Selatan',
            'latitude' => -6.2735,
            'longitude' => 106.8205,
            'is_primary' => true,
        ]);

        Address::create([
            'user_id' => $owner->id,
            'label' => 'Kantor',
            'address_line' => 'Sudirman Central Business District (SCBD) Lot 21',
            'city' => 'Jakarta Selatan',
            'latitude' => -6.2244,
            'longitude' => 106.8098,
            'is_primary' => false,
        ]);

        // 3. Service Provider - Groomer
        $groomer = User::create([
            'name' => 'Indra Grooming Keliling',
            'email' => 'provider@petcare.com',
            'password' => Hash::make('password'),
            'role' => 'service_provider',
            'provider_type' => 'groomer',
            'phone' => '08987654321',
            'bio' => 'Groomer profesional berpengalaman 5 tahun untuk anjing dan kucing galak.',
            'avatar' => 'avatars/groomer-indra.svg',
            'is_verified' => true,
            'latitude' => -6.2615,
            'longitude' => 106.8105,
        ]);

        // Services for Groomer
        Service::create([
            'provider_id' => $groomer->id,
            'name' => 'Grooming Lengkap Kucing/Anjing Kecil',
            'description' => 'Mandi shampoo anti-kutu, potong kuku, bersihkan telinga, blow dry, parfum.',
            'price' => 150000,
            'duration_minutes' => 90,
        ]);

        Service::create([
            'provider_id' => $groomer->id,
            'name' => 'Mandi Sehat Basah',
            'description' => 'Mandi bersih air hangat + potong kuku tanpa cukur bulu.',
            'price' => 90000,
            'duration_minutes' => 45,
        ]);

        // 4. Service Provider - Veterinarian
        $vet = User::create([
            'name' => 'Drh. Sarah Wijaya',
            'email' => 'doctor@petcare.com',
            'password' => Hash::make('password'),
            'role' => 'service_provider',
            'provider_type' => 'veterinarian',
            'phone' => '08771234567',
            'bio' => 'Dokter spesialis hewan kecil lulusan IPB. Menyediakan konsultasi dan pengobatan umum.',
            'avatar' => 'avatars/vet-sarah.svg',
            'certification' => 'certifications/sertifikat-drh-sarah-wijaya.svg',
            'is_verified' => true,
            'latitude' => -6.2415,
            'longitude' => 106.8305,
        ]);

        // Services for Vet
        Service::create([
            'provider_id' => $vet->id,
            'name' => 'Kunjungan & Pemeriksaan Umum',
            'description' => 'Pemeriksaan fisik lengkap hewan di rumah owner.',
            'price' => 120000,
            'duration_minutes' => 60,
        ]);

        Service::create([
            'provider_id' => $vet->id,
            'name' => 'Vaksinasi Kucing F3 / F4 + Buku Vaksin',
            'description' => 'Pemberian vaksin inti lengkap dengan pemeriksaan suhu.',
            'price' => 250000,
            'duration_minutes' => 30,
        ]);

        // 5. Service Provider - Seller
        $seller = User::create([
            'name' => 'Pet Shop Lestari',
            'email' => 'seller@petcare.com',
            'password' => Hash::make('password'),
            'role' => 'service_provider',
            'provider_type' => 'seller',
            'phone' => '081344445555',
            'bio' => 'Menyediakan kebutuhan nutrisi premium dan mainan edukatif hewan.',
            'avatar' => 'avatars/petshop-lestari.svg',
            'is_verified' => true,
        ]);

        // Products for Seller
        Product::create([
            'seller_id' => $seller->id,
            'name' => 'Makanan Kucing Premium Royal Canin 2kg',
            'description' => 'Nutrisi lengkap khusus pertumbuhan bulu dan daya tahan tubuh.',
            'price' => 280000,
            'stock' => 15,
            'image' => 'products/royal-canin-cat-food-2kg.svg',
        ]);

        Product::create([
            'seller_id' => $seller->id,
            'name' => 'Shampoo Hewan FurryMagic 250ml',
            'description' => 'Mencegah ketombe, melembutkan bulu kasar, wangi lavender.',
            'price' => 65000,
            'stock' => 20,
            'image' => 'products/furrymagic-shampoo-250ml.svg',
        ]);

        Product::create([
            'seller_id' => $seller->id,
            'name' => 'Tempat Tidur Kucing Empuk CozyNest',
            'description' => 'Diameter 40cm, bahan beludru hangat dan mudah dicuci.',
            'price' => 110000,
            'stock' => 8,
            'image' => 'products/cozynest-cat-bed.svg',
        ]);

        // 6. Unverified Veterinarian (For Testing Admin Verification Approval)
        User::create([
            'name' => 'Drh. Toni Hartono',
            'email' => 'unverified-vet@petcare.com',
            'password' => Hash::make('password'),
            'role' => 'service_provider',
            'provider_type' => 'veterinarian',
            'phone' => '085522223333',
            'bio' => 'Dokter hewan senior. Membutuhkan persetujuan sertifikasi berkas.',
            'avatar' => 'avatars/vet-toni.svg',
            'certification' => 'certifications/sertifikat-drh-toni-hartono.svg',
            'is_verified' => false,
        ]);

        // Add schedules for Groomer and Vet (Monday to Friday, 9:00 - 17:00)
        $providers = [$groomer->id, $vet->id];
        foreach ($providers as $provId) {
            for ($day = 1; $day <= 5; $day++) {
                ProviderSchedule::create([
                    'user_id' => $provId,
                    'day_of_week' => $day,
                    'start_time' => '09:00',
                    'end_time' => '17:00',
                    'is_available' => true,
                ]);
            }
        }
    }
}

