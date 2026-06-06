<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('projects.index') }}" class="p-2 bg-white rounded-full shadow-sm hover:bg-gray-50 transition">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-black text-2xl text-gray-900 tracking-tight">Konfigurasi Project Baru</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl mb-6 shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0"><svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg></div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pengisian:</h3>
                            <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="p-1.5 bg-blue-100 text-blue-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></span>
                        Informasi Dasar
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Project</label>
                            <input type="text" name="nama_project" required placeholder="Contoh: Survei Demografi Desa" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Singkat</label>
                            <textarea name="deskripsi" rows="3" placeholder="Tujuan project ini adalah..." class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status Project</label>
                            <select name="status" class="w-full rounded-xl border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm transition">
                                <option value="pending">Persiapan (Pending)</option>
                                <option value="on_progress">Berjalan (On Progress)</option>
                                <option value="completed">Selesai (Completed)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-2 flex items-center gap-2">
                        <span class="p-1.5 bg-emerald-100 text-emerald-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                        Fitur Wajib Responden
                    </h3>
                    <p class="text-sm text-gray-500 mb-6">Tentukan modul bawaan apa saja yang wajib diisi oleh surveyor di lapangan.</p>

                    <div class="space-y-4">
                        <label class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 cursor-pointer hover:bg-gray-100 transition">
                            <div>
                                <h4 class="font-bold text-gray-900">Modul Kamera & Galeri</h4>
                                <p class="text-xs text-gray-500 mt-0.5">Wajibkan surveyor mengunggah foto responden/objek observasi.</p>
                            </div>
                            <div class="relative inline-flex items-center">
                                <input type="checkbox" name="has_photo" value="1" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </div>
                        </label>

                        <label class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 cursor-pointer hover:bg-gray-100 transition">
                            <div>
                                <h4 class="font-bold text-gray-900">Modul Validasi Umur</h4>
                                <p class="text-xs text-gray-500 mt-0.5">Tampilkan kolom Tempat, Tanggal Lahir dan kalkulasi usia otomatis.</p>
                            </div>
                            <div class="relative inline-flex items-center">
                                <input type="checkbox" name="has_age_calc" value="1" checked class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <span class="p-1.5 bg-indigo-100 text-indigo-600 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg></span>
                                Builder Form Dinamis
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Tambahkan field spesifik yang dibutuhkan khusus untuk project ini.</p>
                        </div>
                        <button type="button" onclick="addField()" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 text-sm font-bold rounded-xl transition">
                            + Tambah Field Baru
                        </button>
                    </div>

                    <div id="dynamic-fields-container" class="space-y-3">
                        </div>

                    <div id="empty-state" class="text-center py-8 bg-gray-50 border border-dashed border-gray-300 rounded-2xl">
                        <p class="text-sm text-gray-500">Belum ada field tambahan.<br>Klik tombol <b>"+ Tambah Field Baru"</b> jika memerlukan data khusus.</p>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="inline-flex items-center px-8 py-3 bg-gray-900 hover:bg-black text-white text-sm font-bold rounded-xl shadow-lg transition transform hover:-translate-y-1">
                        Buat Project Sekarang
                    </button>
                </div>
            </form>

        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        let fieldIndex = 0;
        const container = document.getElementById('dynamic-fields-container');
        const emptyState = document.getElementById('empty-state');

        // Fungsi PAKSA untuk menghitung ulang index array setelah digeser
        function updateIndices() {
            const rows = container.querySelectorAll('.field-row');
            rows.forEach((row, index) => {
                // Gunakan querySelector langsung ke tag-nya agar tidak gagal
                const nameInput = row.querySelector('input[type="text"]');
                const typeSelect = row.querySelector('select');

                if (nameInput) {
                    nameInput.setAttribute('name', `fields[${index}][name]`);
                }
                if (typeSelect) {
                    typeSelect.setAttribute('name', `fields[${index}][type]`);
                }
            });
        }

        // Mengaktifkan fitur Drag-and-Drop
        new Sortable(container, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'bg-indigo-50',
            onEnd: function () {
                updateIndices(); // Panggil fungsi saat geser selesai
            }
        });

        function addField() {
            emptyState.style.display = 'none';

            const html = `
                <div class="flex flex-col sm:flex-row items-center gap-3 p-4 bg-white border border-indigo-100 rounded-2xl shadow-sm field-row transition-all">
                    <div class="drag-handle cursor-grab active:cursor-grabbing p-2 text-gray-400 hover:text-indigo-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                    </div>
                    <div class="w-full sm:flex-1">
                        <label class="block text-xs font-bold text-gray-500 mb-1">Nama Kolom (Kiri ke Kanan)</label>
                        <input type="text" required placeholder="Contoh: Nama Lengkap / NIK" class="w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                    <div class="w-full sm:w-48">
                        <label class="block text-xs font-bold text-gray-500 mb-1">Tipe Inputan</label>
                        <select class="w-full rounded-xl border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 transition">
                            <option value="text">Teks Pendek</option>
                            <option value="number">Angka / Nominal</option>
                            <option value="date">Kalender (Tanggal)</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-auto pt-5">
                        <button type="button" onclick="removeField(this)" class="w-full sm:w-auto p-2.5 text-rose-500 hover:bg-rose-50 border border-transparent hover:border-rose-100 rounded-xl transition flex justify-center items-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', html);
            updateIndices(); // Rapikan index setelah ditambah
        }

        function removeField(buttonElement) {
            buttonElement.closest('.field-row').remove();
            if (container.children.length === 0) emptyState.style.display = 'block';
            updateIndices(); // Rapikan index setelah dihapus
        }
    </script>
</x-app-layout>
