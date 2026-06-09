# Dokumen Desain Sistem & Arsitektur PawPal

Dokumen ini berisi spesifikasi teknis arsitektur, diagram aliran proses, diagram relasi entitas database (ERD), diagram kelas, diagram use case, serta tabel pemetaan kode dari aplikasi perayap dan pemesanan layanan perawatan hewan **PawPal**.

---

## 1. Diagram Arsitektur (Architecture Diagram)
Aplikasi PawPal dibangun menggunakan arsitektur **MVC (Model-View-Controller)** yang terstruktur dan aman berbasis PHP (Laravel Framework) serta dikompilasi secara dinamis dengan Vite & Tailwind/Bootstrap 5.

```mermaid
graph TD
    Client["Client / Web Browser"] <-->|HTTP Requests / Responses| WebServer["Web Server (Nginx / Apache)"]
    WebServer <-->|Routing & Middleware| LaravelCore["Laravel App Core (PHP)"]
    
    subgraph LaravelMVC["Laravel MVC Architecture"]
        LaravelCore <--> Controller["Controllers (AuthController, PetController, dll.)"]
        Controller <--> Model["Models (User, Pet, Booking, Order, dll.)"]
        Controller <--> View["Blade Templates (HTML/CSS/JS via Vite)"]
    end
    
    Model <--> Database["MySQL Database"]
    LaravelCore <--> Storage["File Storage (avatars, pdf_records, product_images)"]
    
    style Client fill:#ecf3ef,stroke:#759a83,stroke-width:2px
    style Database fill:#fcece6,stroke:#b05634,stroke-width:2px
    style View fill:#fff9f3,stroke:#eadacb,stroke-width:2px
```

---

## 2. Diagram Use Case (Use Case Diagram)
Diagram berikut menggambarkan interaksi antara tiga aktor utama (**Pet Owner**, **Service Provider**, dan **Admin**) dengan fitur-fitur di dalam sistem PawPal.

```mermaid
graph TD
    subgraph Actors
        PO["🐾 Pet Owner (User)"]
        SP["🧑‍⚕️ Service Provider (Groomer/Vet/Sitter)"]
        ADM["📊 System Administrator"]
    end

    subgraph Use Cases
        UC1["Daftar / Masuk Akun"]
        UC2["Kelola Profil Hewan Peliharaan"]
        UC3["Cari & Pesan Layanan"]
        UC4["Belanja Produk Marketplace"]
        UC5["Kelola Alamat Pengiriman"]
        UC6["Akses Rekam Medis Hewan"]
        UC7["Beri Ulasan / Rating"]
        UC8["Kelola Jadwal & Layanan"]
        UC9["Kelola Transaksi & Produk"]
        UC10["Verifikasi Penyedia Layanan"]
        UC11["Kelola Pengguna (User Management)"]
    end

    PO --> UC1
    PO --> UC2
    PO --> UC3
    PO --> UC4
    PO --> UC5
    PO --> UC6
    PO --> UC7

    SP --> UC1
    SP --> UC8
    SP --> UC9

    ADM --> UC1
    ADM --> UC10
    ADM --> UC11
    ADM --> UC8
    ADM --> UC9
```

---

## 3. Diagram Relasi Entitas (Entity Relationship Diagram - ERD)
Struktur tabel database relasional yang menyimpan seluruh informasi inti sistem PawPal.

```mermaid
erDiagram
    USERS ||--o{ PETS : "owns"
    USERS ||--o{ ADDRESSES : "has"
    USERS ||--o{ SERVICES : "offers"
    USERS ||--o{ PRODUCTS : "sells"
    USERS ||--o{ PROVIDER_SCHEDULES : "manages"
    USERS ||--o{ BOOKINGS : "books (as owner)"
    USERS ||--o{ BOOKINGS : "provides (as provider)"
    USERS ||--o{ ORDERS : "purchases"
    USERS ||--o{ REVIEWS : "writes (as owner)"
    USERS ||--o{ REVIEWS : "receives (as provider)"
    
    PETS ||--o{ BOOKINGS : "attends"
    PETS ||--o{ MEDICAL_RECORDS : "has history"
    
    SERVICES ||--o{ BOOKINGS : "associated_with"
    ADDRESSES ||--o{ BOOKINGS : "destination"
    
    BOOKINGS ||--o| MEDICAL_RECORDS : "results in"
    BOOKINGS ||--o| REVIEWS : "evaluated by"
    
    ORDERS ||--|{ ORDER_ITEMS : "contains"
    PRODUCTS ||--o{ ORDER_ITEMS : "ordered"
    
    USERS {
        bigint id PK
        string name
        string email
        string password
        string role "admin | service_provider | pet_owner"
        string phone
        string bio
        boolean is_verified
        boolean is_active
        string avatar
    }
    
    PETS {
        bigint id PK
        bigint user_id FK
        string name
        string type
        string breed
        int age
        decimal weight
        string photo
        text health_notes
    }
    
    ADDRESSES {
        bigint id PK
        bigint user_id FK
        string label
        text address_line
        string city
        decimal latitude
        decimal longitude
        boolean is_primary
    }
    
    SERVICES {
        bigint id PK
        bigint provider_id FK
        string name
        text description
        decimal price
        int duration_minutes
    }
    
    PRODUCTS {
        bigint id PK
        bigint seller_id FK
        string name
        text description
        decimal price
        int stock
        string image
    }
    
    BOOKINGS {
        bigint id PK
        bigint pet_owner_id FK
        bigint provider_id FK
        bigint pet_id FK
        bigint service_id FK
        date booking_date
        time start_time
        string status "pending | confirmed | completed | cancelled"
        decimal total_price
        bigint address_id FK
        text notes
    }
    
    MEDICAL_RECORDS {
        bigint id PK
        bigint pet_id FK
        bigint vet_id FK
        bigint booking_id FK
        date visit_date
        text diagnosis
        text treatment
        text notes
        string pdf_path
    }
```

---

## 4. Diagram Urutan (Sequence Diagram - Booking Layanan)
Proses berurutan saat seorang Pet Owner melakukan booking layanan perawatan hewan melalui aplikasi.

```mermaid
sequenceDiagram
    autonumber
    actor PetOwner as Pet Owner
    participant View as Blade View (Booking)
    participant Controller as BookingController
    participant Model as Booking Model
    participant Database as Database (MySQL)

    PetOwner->>View: Pilih Layanan & Tanggal/Waktu
    PetOwner->>View: Pilih Hewan & Alamat
    PetOwner->>View: Klik 'Lanjutkan / Simpan'
    View->>Controller: POST /booking/store (Data Booking)
    activate Controller
    Controller->>Controller: Validasi data input
    Controller->>Model: Create new Booking object
    activate Model
    Model->>Database: INSERT INTO bookings values (...)
    Database-->>Model: Success & Return Booking ID
    Model-->>Controller: Booking instance
    deactivate Model
    Controller-->>View: Redirect ke Halaman Pemesanan (Success Alert)
    deactivate Controller
    View-->>PetOwner: Tampilkan riwayat booking (Status: Pending)
```

---

## 5. Diagram Kelas (Class Diagram - Pola MVC)
Representasi kelas-kelas utama (Controllers & Models) beserta hubungannya di dalam kode Laravel PawPal.

```mermaid
classDiagram
    class Controller {
        <<Framework>>
    }
    class Model {
        <<Framework>>
    }
    
    class AuthController {
        +showLogin()
        +login(Request)
        +showRegister()
        +register(Request)
        +logout()
    }
    
    class DashboardController {
        +index()
        +ownerDashboard()
        +providerDashboard()
        +adminDashboard()
    }
    
    class PetController {
        +index()
        +create()
        +store(Request)
        +show(Pet)
        +edit(Pet)
        +update(Request, Pet)
        +destroy(Pet)
    }
    
    class BookingController {
        +search(Request)
        +showCreate(User)
        +store(Request)
        +updateStatus(Request, Booking)
    }

    class User {
        +id: bigint
        +name: string
        +role: string
        +isAdmin()
        +isPetOwner()
        +isServiceProvider()
        +pets() Relation
        +addresses() Relation
    }
    
    class Pet {
        +id: bigint
        +name: string
        +type: string
        +age: int
        +owner() Relation
    }
    
    class Booking {
        +id: bigint
        +booking_date: date
        +status: string
        +pet() Relation
        +provider() Relation
    }
    
    AuthController --|> Controller
    DashboardController --|> Controller
    PetController --|> Controller
    BookingController --|> Controller
    
    User --|> Model
    Pet --|> Model
    Booking --|> Model
    
    User "1" *-- "0..*" Pet : owns
    User "1" *-- "0..*" Booking : schedules
    Pet "1" *-- "0..*" Booking : attends
```

---

## 6. Diagram Aktivitas (Activity Diagram - Booking Flow)
Diagram alir aktivitas alur booking dari awal hingga selesai.

```mermaid
stateDiagram-v2
    [*] --> CariProvider: Klik Cari Penyedia Layanan
    CariProvider --> PilihProvider: Pilih Kategori (Groomer/Vet/Sitter)
    PilihProvider --> FormBooking: Klik Booking
    FormBooking --> PilihLayanan: Tentukan Layanan Terkait
    PilihLayanan --> PilihTanggalWaktu: Tentukan Hari & Jam
    PilihTanggalWaktu --> PilihPeliharaan: Pilih Hewan Terdaftar
    PilihPeliharaan --> PilihAlamat: Pilih Alamat Penjemputan
    PilihAlamat --> SubmitBooking: Klik Lanjutkan
    SubmitBooking --> StatusPending: Data Booking Dibuat
    StatusPending --> ProviderReview: Notifikasi Ditampilkan ke Provider
    
    state ProviderReview {
        [*] --> MenungguKeputusan
        MenungguKeputusan --> Konfirmasi: Setuju
        MenungguKeputusan --> Ditolak: Tolak / Batalkan
    }
    
    Ditolak --> [*]: Booking Dibatalkan
    Konfirmasi --> SelesaiLayanan: Provider Menyelesaikan Tugas
    SelesaiLayanan --> UploadRekamMedis: Jika Dokter Vet, Lampirkan PDF
    UploadRekamMedis --> ReviewUlasan: Pemilik Hewan Memberikan Ulasan/Rating
    ReviewUlasan --> [*]: Selesai
```

---

## 7. Tabel Pemetaan (Mapping Table)

### A. Pemetaan Database ke Model Eloquent
| Nama Tabel | Nama Model | Deskripsi |
| :--- | :--- | :--- |
| `users` | `User` | Menyimpan kredensial pengguna, peran, status verifikasi, dan biodata |
| `pets` | `Pet` | Profil data hewan peliharaan milik Pet Owner |
| `addresses` | `Address` | Alamat pengiriman/kunjungan lengkap beserta koordinat GPS |
| `services` | `Service` | Kategori layanan perawatan hewan yang ditawarkan oleh Provider |
| `products` | `Product` | Katalog produk pakan/perlengkapan hewan di marketplace |
| `provider_schedules` | `ProviderSchedule` | Jam dan hari operasional ketersediaan milik provider |
| `bookings` | `Booking` | Pemesanan kunjungan layanan (grooming/dokter/sitter) |
| `medical_records` | `MedicalRecord` | Digitalisasi rekam medis hewan pasca kunjungan dokter hewan |
| `reviews` | `Review` | Penilaian bintang (1-5) dan komentar dari Pet Owner untuk Provider |
| `orders` | `Order` | Transaksi invoice pesanan produk marketplace |
| `order_items` | `OrderItem` | Rincian produk dan kuantitas di dalam suatu pesanan/order |

### B. Pemetaan Rute, Kontroler, dan Tampilan (Routes, Controllers & Views)
| HTTP Method | URL Path | Nama Rute | Controller & Method | Blade View / Redirect |
| :--- | :--- | :--- | :--- | :--- |
| **GET** | `/` | `home` | *Closure* | `welcome.blade.php` |
| **GET** | `/login` | `login` | `AuthController@showLogin` | `auth/login.blade.php` |
| **POST** | `/login` | — | `AuthController@login` | *Redirect ke* `/dashboard` |
| **GET** | `/register` | `register` | `AuthController@showRegister` | `auth/register.blade.php` |
| **POST** | `/register` | — | `AuthController@register` | *Redirect ke* `/dashboard` |
| **POST** | `/logout` | `logout` | `AuthController@logout` | *Redirect ke* `/login` |
| **GET** | `/dashboard` | `dashboard` | `DashboardController@index` | *Redirect berdasarkan peran* |
| **GET** | `/owner/dashboard` | `owner.dashboard` | `DashboardController@ownerDashboard` | `owner/dashboard.blade.php` |
| **GET** | `/pets` | `pets.index` | `PetController@index` | `owner/pets/index.blade.php` |
| **GET** | `/pets/create` | `pets.create` | `PetController@create` | `owner/pets/create.blade.php` |
| **POST** | `/pets` | `pets.store` | `PetController@store` | *Redirect ke* `pets.index` |
| **GET** | `/pets/{pet}` | `pets.show` | `PetController@show` | `owner/pets/show.blade.php` |
| **GET** | `/pets/{pet}/edit` | `pets.edit` | `PetController@edit` | `owner/pets/edit.blade.php` |
| **PUT** | `/pets/{pet}` | `pets.update` | `PetController@update` | *Redirect ke* `pets.show` |
| **DELETE** | `/pets/{pet}` | `pets.destroy` | `PetController@destroy` | *Redirect ke* `pets.index` |
| **GET** | `/addresses` | `addresses.index` | `AddressController@index` | `owner/addresses/index.blade.php` |
| **GET** | `/booking/search` | `booking.search` | `BookingController@search` | `owner/search_providers.blade.php` |
| **GET** | `/booking/create/{provider}` | `booking.create` | `BookingController@showCreate` | `owner/create_booking.blade.php` |
| **POST** | `/booking/store` | `booking.store` | `BookingController@store` | *Redirect ke* `bookings.index` |
| **GET** | `/marketplace` | `marketplace.index` | `MarketplaceController@index` | `marketplace/index.blade.php` |
| **GET** | `/marketplace/cart` | `marketplace.cart` | `MarketplaceController@cart` | `marketplace/cart.blade.php` |
