<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('projects.show', $projectId) }}" class="p-2 bg-white rounded-full shadow-sm hover:bg-gray-50 transition">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-black text-2xl text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-500 tracking-tight">
                    Input Responden Baru
                </h2>
                <p class="text-sm text-gray-500 mt-1">Sistem akan otomatis mengompres foto sebelum diunggah.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl shadow-sm">
                    <ul class="list-disc list-inside text-sm font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white/80 backdrop-blur-xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 rounded-3xl overflow-hidden">
                <form id="respondent-form" action="{{ route('respondents.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf

                    <input type="hidden" name="project_id" value="{{ $projectId }}">

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-6">Data Inti</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Induk Kependudukan (NIK)</label>
                                <input type="text" name="nik" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm font-mono" placeholder="Masukkan 16 digit NIK">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="nama" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm" placeholder="Sesuai KTP">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Domisili</label>
                                <textarea name="alamat" rows="3" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm"></textarea>
                            </div>

                            <div class="md:col-span-2 p-6 bg-blue-50/50 border border-blue-100 rounded-2xl">

                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                                    <div>
                                        <label class="block text-sm font-semibold text-blue-900">Pilih Metode Input Foto</label>
                                        <p class="text-xs text-gray-500 mt-1">Gunakan kamera untuk memotret langsung, atau galeri untuk memilih banyak foto.</p>
                                    </div>

                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="checkbox" id="use-watermark" class="sr-only peer">
                                        <div class="relative w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        <span class="ms-3 text-sm font-bold text-blue-900">Gunakan Watermark</span>
                                    </label>
                                </div>

                                <div id="gps-indicator" class="hidden mb-4">
                                    <span id="gps-status" class="text-xs font-bold text-gray-600 bg-gray-100 px-3 py-1.5 rounded-lg border border-gray-200 shadow-sm flex inline-flex items-center">
                                        <svg class="w-4 h-4 mr-1 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        Menunggu akses lokasi...
                                    </span>
                                </div>

                                <div class="flex flex-wrap gap-3 mt-2">
                                    <button type="button" id="btn-camera" class="inline-flex items-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Buka Kamera
                                    </button>

                                    <button type="button" id="btn-gallery" class="inline-flex items-center px-5 py-2.5 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 text-sm font-bold rounded-xl shadow-sm transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        Pilih dari Galeri
                                    </button>
                                </div>

                                <input type="file" id="album-input" name="album[]" accept="image/*" class="hidden">

                                <p id="compress-status" class="mt-4 text-xs font-semibold text-emerald-600 hidden">
                                    Memproses kompresi foto...
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status Input Sistem Induk</label>
                                <select name="status" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm">
                                    <option value="belum_diinput">Belum Diinput</option>
                                    <option value="sudah_diinput">Sudah Diinput</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                                <input type="text" name="keterangan" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm">
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <div class="flex justify-between items-center border-b pb-2 mb-6">
                            <h3 class="text-lg font-bold text-gray-900">Data Tambahan (Dinamis)</h3>
                            <button type="button" id="add-field-btn" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Tambah Field Khusus
                            </button>
                        </div>

                        <div id="dynamic-fields-container" class="space-y-4">
                            </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 font-bold text-white transition-all duration-300 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl hover:from-emerald-400 hover:to-teal-500 shadow-[0_8px_30px_rgb(16,185,129,0.3)] hover:shadow-[0_8px_30px_rgb(16,185,129,0.5)] hover:-translate-y-1">
                            Simpan Data Responden
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.2.1/compressor.min.js"></script>

   <script>
        // --- 1. LOGIKA TOGGLE WATERMARK & GEOLOCATION ---
        const useWatermarkToggle = document.getElementById('use-watermark');
        const gpsIndicator = document.getElementById('gps-indicator');
        const gpsStatus = document.getElementById('gps-status');
        window.currentLocation = "Lokasi tidak diketahui/diizinkan";

        // Pantau jika tombol watermark diklik
        useWatermarkToggle.addEventListener('change', function() {
            if (this.checked) {
                // Munculkan indikator & minta akses GPS
                gpsIndicator.classList.remove('hidden');
                gpsStatus.innerHTML = `<svg class="w-4 h-4 mr-1 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Mendeteksi Lokasi...`;
                gpsStatus.className = "text-xs font-bold text-amber-700 bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200 shadow-sm inline-flex items-center";

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude.toFixed(5);
                            const lng = position.coords.longitude.toFixed(5);
                            window.currentLocation = `Lat: ${lat}, Long: ${lng}`;

                            gpsStatus.innerHTML = `✓ GPS Aktif: ${window.currentLocation}`;
                            gpsStatus.className = "text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200 shadow-sm inline-flex items-center";
                        },
                        (error) => {
                            window.currentLocation = "Lokasi gagal/ditolak";
                            gpsStatus.innerHTML = `⚠ Izin Lokasi Ditolak`;
                            gpsStatus.className = "text-xs font-bold text-rose-700 bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-200 shadow-sm inline-flex items-center";
                        },
                        { enableHighAccuracy: true, timeout: 10000 }
                    );
                } else {
                    gpsStatus.innerHTML = `⚠ Browser tidak mendukung GPS`;
                }
            } else {
                // Sembunyikan indikator jika watermark dimatikan
                gpsIndicator.classList.add('hidden');
            }
        });

        // --- 1.5 LOGIKA TOMBOL KAMERA & GALERI ---
        const btnCamera = document.getElementById('btn-camera');
        const btnGallery = document.getElementById('btn-gallery');
        const hiddenAlbumInput = document.getElementById('album-input');

        // Jika tombol Kamera diklik
        btnCamera.addEventListener('click', function() {
            hiddenAlbumInput.setAttribute('capture', 'environment'); // Paksa OS buka kamera
            hiddenAlbumInput.removeAttribute('multiple'); // Kamera hanya bisa 1 jepretan
            hiddenAlbumInput.click(); // Picu input file secara tersembunyi
        });

        // Jika tombol Galeri diklik
        btnGallery.addEventListener('click', function() {
            hiddenAlbumInput.removeAttribute('capture'); // Hapus paksaan kamera
            hiddenAlbumInput.setAttribute('multiple', 'multiple'); // Izinkan pilih banyak foto
            hiddenAlbumInput.click(); // Picu input file secara tersembunyi
        });

        // --- 2. LOGIKA KOMPRESI FOTO ---
        const albumInput = document.getElementById('album-input');
        const statusText = document.getElementById('compress-status');

        albumInput.addEventListener('change', async function(e) {
            const files = e.target.files;
            if (files.length === 0) return;

            statusText.classList.remove('hidden');
            statusText.classList.replace('text-blue-600', 'text-emerald-600');
            statusText.innerHTML = `<svg class="w-4 h-4 inline mr-1 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses ${files.length} foto...`;

            const dataTransfer = new DataTransfer();

            for (let file of files) {
                await new Promise((resolve, reject) => {

                    // Siapkan pengaturan dasar Compressor
                    let compressOptions = {
                        quality: 0.7,
                        maxWidth: 1200,
                        success(result) {
                            const compressedFile = new File([result], file.name, {
                                type: result.type,
                                lastModified: Date.now(),
                            });
                            dataTransfer.items.add(compressedFile);
                            resolve();
                        },
                        error: reject
                    };

                    // JIKA TOGGLE WATERMARK AKTIF, tambahkan logika menggambar (drew)
                    if (useWatermarkToggle.checked) {
                        compressOptions.drew = function(context, canvas) {
                            context.fillStyle = 'white';
                            context.font = 'bold 24px Arial';
                            context.shadowColor = 'black';
                            context.shadowBlur = 6;
                            context.shadowOffsetX = 2;
                            context.shadowOffsetY = 2;

                            const date = new Date();
                            const timestamp = date.toLocaleDateString('id-ID') + ' - ' + date.toLocaleTimeString('id-ID');
                            const textWaktu = `Waktu  : ${timestamp}`;
                            const textLokasi = `Lokasi : ${window.currentLocation}`;
                            const textProject = `Project: NEXORA Survey`;

                            const paddingX = 20;
                            const paddingY = canvas.height - 80;

                            context.fillText(textProject, paddingX, paddingY);
                            context.fillText(textWaktu, paddingX, paddingY + 25);
                            context.fillText(textLokasi, paddingX, paddingY + 50);
                        };
                    }

                    // Eksekusi Kompresi
                    new Compressor(file, compressOptions);
                });
            }

            albumInput.files = dataTransfer.files;
            statusText.classList.replace('text-emerald-600', 'text-blue-600');
            statusText.innerHTML = `✓ ${files.length} foto siap diunggah!`;
        });

        // --- 3. LOGIKA DYNAMIC FIELDS (TETAP SAMA) ---
        document.getElementById('add-field-btn').addEventListener('click', function() {
            const container = document.getElementById('dynamic-fields-container');
            const row = document.createElement('div');
            row.className = 'flex flex-col sm:flex-row gap-4 items-start sm:items-end p-4 bg-gray-50 border border-gray-100 rounded-xl relative group';

            row.innerHTML = `
                <div class="w-full sm:w-1/3">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Field</label>
                    <input type="text" class="field-name-input block w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Misal: Pekerjaan">
                </div>
                <div class="w-full sm:w-2/3">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Isi Data</label>
                    <div class="flex gap-2">
                        <input type="text" class="field-value-input block w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Misal: Petani Tambak">
                        <button type="button" class="remove-field-btn p-2.5 bg-rose-100 text-rose-600 rounded-lg hover:bg-rose-500 hover:text-white transition" title="Hapus Field">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            `;
            container.appendChild(row);

            const nameInput = row.querySelector('.field-name-input');
            const valInput = row.querySelector('.field-value-input');

            nameInput.addEventListener('input', function() {
                let safeName = this.value.trim().replace(/[^a-zA-Z0-9]/g, '_').toLowerCase();
                valInput.name = safeName;
            });

            row.querySelector('.remove-field-btn').addEventListener('click', function() {
                row.remove();
            });
        });

        document.getElementById('respondent-form').addEventListener('submit', function(e) {
            const nameInputs = document.querySelectorAll('.field-name-input');
            for (let input of nameInputs) {
                if (input.value.trim() === '') {
                    e.preventDefault();
                    alert('Terdapat "Nama Field" dinamis yang masih kosong. Harap isi namanya atau hapus baris field tersebut.');
                    input.focus();
                    return;
                }
            }
        });
    </script>
</x-app-layout>
