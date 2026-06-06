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

                    @php $project = $respondent->project; @endphp

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-6">Data Responden</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if(is_array($project->master_fields) && count($project->master_fields) > 0)
                                @foreach($project->master_fields as $field)
                                    @php
                                        $fieldKey = \Illuminate\Support\Str::slug($field['name'], '_');
                                        $inputType = 'text';
                                        if($field['type'] == 'number') $inputType = 'number';
                                        if($field['type'] == 'date') $inputType = 'date';

                                        // Mengambil nilai lama dari JSON data_tambahan
                                        $oldValue = is_array($respondent->data_tambahan) && isset($respondent->data_tambahan[$fieldKey])
                                                    ? $respondent->data_tambahan[$fieldKey]
                                                    : '';
                                    @endphp
                                    <div class="{{ $inputType === 'text' ? 'md:col-span-2' : '' }}">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ $field['name'] }}</label>
                                        <input type="{{ $inputType }}" name="{{ $fieldKey }}" value="{{ old($fieldKey, $oldValue) }}" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm" placeholder="Masukkan {{ $field['name'] }}">
                                    </div>
                                @endforeach
                            @else
                                <div class="md:col-span-2 p-4 bg-yellow-50 text-yellow-700 rounded-xl border border-yellow-200 text-sm font-medium">
                                    Project ini belum memiliki konfigurasi field data.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-6">Data Sistem</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            @if($project->has_age_calc)
                                @php
                                    $oldTglLahir = is_array($respondent->data_tambahan) && isset($respondent->data_tambahan['tanggal_lahir'])
                                                ? $respondent->data_tambahan['tanggal_lahir'] : '';
                                @endphp
                                <div class="md:col-span-2 p-5 bg-purple-50/50 border border-purple-100 rounded-2xl">
                                    <label class="block text-sm font-semibold text-purple-900 mb-2">Tanggal Lahir (Kalkulasi Umur)</label>
                                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $oldTglLahir) }}" required class="block w-full md:w-1/2 px-4 py-3 bg-white border border-purple-200 rounded-xl focus:ring-purple-500 focus:border-purple-500 transition shadow-sm">
                                </div>
                            @endif

                            @if($project->has_photo)
                            <div class="md:col-span-2 p-6 bg-blue-50/50 border border-blue-100 rounded-2xl flex flex-col md:flex-row gap-6 items-center">
                                @if(is_array($respondent->album) && count($respondent->album) > 0)
                                    <div class="shrink-0 text-center">
                                        <p class="text-xs font-bold text-blue-900 mb-2">ALBUM SAAT INI ({{ count($respondent->album) }})</p>
                                        <div class="flex gap-2 overflow-x-auto max-w-[200px] pb-2">
                                            @foreach($respondent->album as $foto)
                                                <img src="{{ $foto }}" class="w-16 h-16 object-cover rounded-lg shadow-sm border border-blue-200 shrink-0">
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <div class="flex-grow w-full">
                                    <label class="block text-sm font-semibold text-blue-900 mb-2">Unggah Album Baru (Kamera / Galeri)</label>
                                    <input type="file" id="album-input" name="album[]" accept="image/*" multiple class="block w-full text-sm text-blue-700 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition cursor-pointer bg-white border border-blue-200 shadow-sm">
                                    <p class="mt-2 text-xs text-blue-600">Jika Anda mengunggah foto baru, foto album lama akan dihapus dan ditimpa.</p>
                                    <p id="compress-status" class="mt-2 text-xs font-semibold text-emerald-600 hidden">Memproses kompresi...</p>
                                </div>
                            </div>
                            @endif

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status Input</label>
                                <select name="status" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
                                    <option value="belum_diinput" {{ $respondent->status == 'belum_diinput' ? 'selected' : '' }}>Belum Diinput</option>
                                    <option value="sudah_diinput" {{ $respondent->status == 'sudah_diinput' ? 'selected' : '' }}>Sudah Diinput</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                                <input type="text" name="keterangan" value="{{ old('keterangan', $respondent->keterangan) }}" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 font-bold text-white transition-all duration-300 bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-xl hover:from-indigo-400 hover:to-indigo-500 shadow-[0_8px_30px_rgb(99,102,241,0.3)] hover:shadow-[0_8px_30px_rgb(99,102,241,0.5)] hover:-translate-y-1">
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

        if (albumInput) {
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
        }
    </script>
</x-app-layout>
