# NEXORA (Next Generation Observation and Response Acquisition) 🚀

NEXORA adalah sistem informasi manajemen survei dan observasi lapangan berbasis web yang dirancang untuk digitalisasi pengolahan data secara efisien, mudah, dan aman. 

Aplikasi ini sangat cocok digunakan oleh *surveyor* di lapangan berkat fitur integrasi kamera *native*, pencatatan koordinat (GPS), dan penyimpanan *cloud* yang dioptimalkan.

## ✨ Fitur Unggulan

- **Manajemen Project & Responden:** Kelola banyak project survei sekaligus dengan pendataan responden yang terstruktur.
- **Dynamic Fields (Formulir Fleksibel):** Mampu menambahkan bidang data baru (seperti penghasilan, pekerjaan, dll) secara dinamis (*on-the-fly*) tanpa merombak database.
- **Client-side Image Compression:** Mengompres foto secara otomatis di perangkat pengguna sebelum dikirim ke *server*, menghemat kuota internet dan mempercepat proses unggah.
- **Smart Camera & Geotagging:** Membuka kamera *smartphone* secara langsung dari *browser* dan otomatis menempelkan *watermark* (Titik Koordinat GPS dan Timestamp) pada foto responden.
- **Cloud Storage Integration:** Seluruh foto dan album disimpan secara aman menggunakan **Cloudinary API**, menjaga *server* utama tetap ringan.
- **Advanced Filtering:** Filter data responden berdasarkan rentang usia, tanggal input, status, dan pencarian kata kunci yang canggih.

## 🛠️ Tech Stack

- **Framework Backend:** Laravel (PHP)
- **Frontend & Styling:** Tailwind CSS, Alpine.js (opsional/Vite)
- **Database:** MySQL
- **Third-Party Service:** Cloudinary (Image Hosting)
- **JavaScript Library:** Compressor.js (Image Compression & Canvas Watermark)

## 🚀 Panduan Instalasi (Local Development)

Ikuti langkah-langkah berikut untuk menjalankan NEXORA di komputer lokal Anda:

1. **Clone Repository**
   ```bash
   git clone [https://github.com/rmdhanw/NEXORA.git](https://github.com/rmdhanw/NEXORA.git)
   cd NEXORA
2. **Install Depedensi Backend & Frontend**
   composer install
   npm install
3. **Pengaturan Environtment (.env)**
    Salin file .env.example menjadi .env:
    cp .env.example .env
    Buka file .env, sesuaikan pengaturan database Anda serta wajib menambahkan kredensial Cloudinary :
    DB_DATABASE=nexora
    DB_USERNAME=root
    DB_PASSWORD=

    CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME

4. **Generate Application Key & Migrate Database**
    php artisan key:generate
    php artisan migrate

5. **Build Asset (Tailwind CSS)**
    npm run build

6. **Build Asset (Tailwind CSS)**
