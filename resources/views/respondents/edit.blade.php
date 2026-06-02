<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('projects.show', $respondent->project_id) }}" class="p-2 bg-white rounded-full shadow-sm hover:bg-gray-50 transition">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-black text-2xl text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-500 tracking-tight">
                    Edit Detail Responden
                </h2>
                <p class="text-sm text-gray-500 mt-1">Perbarui data atau ubah isi album foto responden ini.</p>
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

            <div class="bg-white/80 backdrop-blur-xl shadow-sm border border-gray-100 rounded-3xl overflow-hidden">
                <form id="respondent-form" action="{{ route('respondents.update', $respondent->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-6">Data Inti</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">NIK</label>
                                <input type="text" name="nik" required value="{{ old('nik', $respondent->nik) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-indigo-500 font-mono">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="nama" required value="{{ old('nama', $respondent->nama) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" required value="{{ old('tempat_lahir', $respondent->tempat_lahir) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-indigo-500">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" required value="{{ old('tanggal_lahir', $respondent->tanggal_lahir ? $respondent->tanggal_lahir->format('Y-m-d') : '') }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-indigo-500">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Domisili</label>
                                <textarea name="alamat" rows="3" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-indigo-500">{{ old('alamat', $respondent->alamat) }}</textarea>
                            </div>

                            <div class="md:col-span-2 p-6 bg-indigo-50/50 border border-indigo-100 rounded-2xl flex flex-col md:flex-row gap-6 items-center">
                                @if(is_array($respondent->album) && count($respondent->album) > 0)
                                    <div class="shrink-0 text-center">
                                        <p class="text-xs font-bold text-gray-500 mb-2">ALBUM SAAT INI ({{ count($respondent->album) }} FOTO)</p>
                                        <div class="flex gap-2 overflow-x-auto max-w-[200px] pb-2">
                                            @foreach($respondent->album as $foto)
                                                <img src="{{ $foto }}" class="w-16 h-16 object-cover rounded-lg shadow-sm border border-gray-200 shrink-0">
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <div class="flex-grow w-full">
                                    <label class="block text-sm font-semibold text-indigo-900 mb-2">Unggah Album Baru (Pilih banyak foto sekaligus)</label>
                                    <input type="file" id="album-input" name="album[]" accept="image/*" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 transition cursor-pointer">
                                    <p class="mt-2 text-xs text-gray-500">Jika Anda mengunggah foto baru, foto album lama akan dihapus dan ditimpa.</p>
                                    <p id="compress-status" class="mt-2 text-xs font-semibold text-emerald-600 hidden">Memproses kompresi...</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status Input</label>
                                <select name="status" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-indigo-500">
                                    <option value="belum_diinput" {{ $respondent->status == 'belum_diinput' ? 'selected' : '' }}>Belum Diinput</option>
                                    <option value="sudah_diinput" {{ $respondent->status == 'sudah_diinput' ? 'selected' : '' }}>Sudah Diinput</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan</label>
                                <input type="text" name="keterangan" value="{{ old('keterangan', $respondent->keterangan) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <div class="flex justify-between items-center border-b pb-2 mb-6">
                            <h3 class="text-lg font-bold text-gray-900">Data Tambahan (Dinamis)</h3>
                            <button type="button" id="add-field-btn" class="text-sm font-bold text-indigo-600 hover:text-indigo-800 flex items-center">
                                + Tambah Field
                            </button>
                        </div>

                        <div id="dynamic-fields-container" class="space-y-4">
                            @if(is_array($respondent->data_tambahan))
                                @foreach($respondent->data_tambahan as $key => $value)
                                    <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-end p-4 bg-gray-50 border border-gray-100 rounded-xl relative group">
                                        <div class="w-full sm:w-1/3">
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Field</label>
                                            <input type="text" value="{{ str_replace('_', ' ', ucwords($key, '_')) }}" class="field-name-input block w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm" readonly>
                                        </div>
                                        <div class="w-full sm:w-2/3">
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Isi Data</label>
                                            <div class="flex gap-2">
                                                <input type="text" name="{{ $key }}" value="{{ $value }}" class="field-value-input block w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm">
                                                <button type="button" class="remove-field-btn p-2.5 bg-rose-100 text-rose-600 rounded-lg hover:bg-rose-500 hover:text-white transition" title="Hapus">X</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 font-bold text-white transition-all bg-indigo-600 rounded-xl hover:bg-indigo-500 shadow-md">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/compressorjs/1.2.1/compressor.min.js"></script>
    <script>
        // Logika kompresi untuk banyak file (Album)
        const albumInput = document.getElementById('album-input');
        const statusText = document.getElementById('compress-status');

        albumInput.addEventListener('change', async function(e) {
            const files = e.target.files;
            if (files.length === 0) return;

            statusText.classList.remove('hidden');
            statusText.classList.add('text-indigo-600');
            statusText.innerHTML = `Memproses ${files.length} foto...`;

            const dataTransfer = new DataTransfer();

            for (let file of files) {
                await new Promise((resolve, reject) => {
                    new Compressor(file, {
                        quality: 0.6,
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
                    });
                });
            }

            albumInput.files = dataTransfer.files;
            statusText.classList.replace('text-indigo-600', 'text-emerald-600');
            statusText.innerHTML = `✓ ${files.length} foto siap diunggah!`;
        });

        // Logika hapus baris dinamis
        document.getElementById('dynamic-fields-container').addEventListener('click', function(e) {
            if (e.target.closest('.remove-field-btn')) {
                e.target.closest('.group').remove();
            }
        });

        // Logika tambah baris dinamis
        document.getElementById('add-field-btn').addEventListener('click', function() {
            const container = document.getElementById('dynamic-fields-container');
            const row = document.createElement('div');
            row.className = 'flex flex-col sm:flex-row gap-4 items-start sm:items-end p-4 bg-gray-50 border border-gray-100 rounded-xl relative group';

            row.innerHTML = `
                <div class="w-full sm:w-1/3">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Field</label>
                    <input type="text" class="field-name-input block w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm" placeholder="Misal: Pekerjaan">
                </div>
                <div class="w-full sm:w-2/3">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Isi Data</label>
                    <div class="flex gap-2">
                        <input type="text" class="field-value-input block w-full px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm" placeholder="Isi data...">
                        <button type="button" class="remove-field-btn p-2.5 bg-rose-100 text-rose-600 rounded-lg hover:bg-rose-500 hover:text-white transition">X</button>
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
        });
    </script>
</x-app-layout>
