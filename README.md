# NEXORA (Next Generation Observation and Response Acquisition) 

NEXORA adalah sistem informasi manajemen survei, observasi lapangan, dan pembangun formulir dinamis (*Dynamic Form Builder*) berbasis web yang dirancang untuk digitalisasi pengolahan data secara fleksibel, cepat, rapi, dan terstruktur.


---

##  Fitur Unggulan

- **Clean Layered Architecture:** Pemisahan tanggung jawab yang rapi antara Controller (*Thin Controller*), Service Layer (`App\Services`), Form Requests (`App\Http\Requests`), dan Domain Models (`App\Models`).
- **Multi-Form Master Data Builder:** Pengguna dapat membuat banyak Master Form dinamis di dalam satu Project dengan skema *field* yang disesuaikan secara mandiri.
- **Dynamic JSON Fields Engine:** Menambahkan bidang data baru (teks, angka, tanggal) secara dinamis (*on-the-fly*) menggunakan dukungan `JSON` column dan pencarian cepat berbasis **Eloquent Native JSON Query**.
- **Fitur Bawaan Konfigurabel (Toggleable System Features):**
  - **Auto Age Calculator:** Otomatis menghitung dan melakukan filter rentang umur dari tanggal lahir.
  - **Cloud Album Photo:** Upload foto album responden yang langsung dihubungkan ke Cloudinary.
- **Client-Side Image Compression & Watermarking:** Mengompresi foto otomatis di peranti pengguna sebelum dikirim ke Cloudinary untuk menghemat bandwidth.
- **Advanced Filtering & Bulk Delete:** Filter responden berdasarkan kata kunci JSON, rentang usia, tanggal input, serta fitur hapus massal aman (beserta pembersihan berkas di Cloudinary).

---

##  Tech Stack & Arsitektur

- **Framework Backend:** Laravel (PHP 8.2+)
- **Frontend & Styling:** Blade, Tailwind CSS, Alpine.js (Form Builder Engine)
- **Database:** MySQL 8.0+ (Relational + Native JSON Data Type)
- **Third-Party Services:** Cloudinary API (Media & Album Storage Service)
- **Client Compression:** Compressor.js / Canvas Engine

---

##  Struktur Arsitektur Kode (`App/`)

```text
app/
├── Http/
│   ├── Controllers/       # Thin Controllers (Request dispatching & view rendering)
│   │   ├── ProjectController.php
│   │   ├── FormController.php
│   │   └── RespondentController.php
│   └── Requests/          # Isolated Validation Rules
│       ├── StoreProjectRequest.php
│       ├── StoreFormRequest.php
│       └── StoreRespondentRequest.php
├── Models/                # Domain Entities & Relations
│   ├── Project.php
│   ├── Form.php
│   └── Respondent.php
└── Services/              # Core Business Logic Layer
    ├── ProjectService.php
    ├── FormService.php
    ├── RespondentService.php
    └── CloudinaryService.php
```

---

##  Panduan Instalasi (Local Development)

Ikuti langkah-langkah berikut untuk menjalankan NEXORA di komputer lokal Anda:

1. **Clone Repository**
   ```bash
   git clone https://github.com/rmdhanw/NEXORA.git
   cd NEXORA
   ```

2. **Install Dependensi Backend & Frontend**
   ```bash
   composer install
   npm install
   ```

3. **Pengaturan Environment (.env)**
   Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
   Buka file `.env`, sesuaikan pengaturan database dan tambahkan kredensial Cloudinary Anda:
   ```env
   DB_DATABASE=nexora
   DB_USERNAME=root
   DB_PASSWORD=

   CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
   ```

4. **Generate Application Key & Migrate Database**
   ```bash
   php artisan key:generate
   php artisan migrate
   ```

5. **Build Asset (Tailwind CSS)**
   ```bash
   npm run build
   ```

6. **Jalankan Server Lokal**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui `http://127.0.0.1:8000`.

---

##  Lisensi
Dikembangkan untuk pengolahan data dan manajemen survei modern.
