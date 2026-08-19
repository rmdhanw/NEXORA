<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('projects.show', $projectId) }}" class="p-2 bg-white rounded-full shadow-sm hover:bg-gray-50 transition">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-black text-2xl text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-500 tracking-tight">
                    Input Responden Baru {{ isset($form) ? "({$form->title})" : '' }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Sistem akan otomatis mengompres foto sebelum diunggah ke Cloudinary.</p>
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
                    @if(isset($form))
                        <input type="hidden" name="form_id" value="{{ $form->id }}">
                    @endif

                    @php
                        $fieldsToRender = isset($form) && is_array($form->fields_schema) ? $form->fields_schema : ($project->master_fields ?? []);
                        $hasPhotoOption = isset($form) ? $form->has_photo : $project->has_photo;
                        $hasAgeCalcOption = isset($form) ? $form->has_age_calc : $project->has_age_calc;
                    @endphp

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-6">Data Responden</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if(is_array($fieldsToRender) && count($fieldsToRender) > 0)
                                @foreach($fieldsToRender as $field)
                                    @php
                                        $fieldKey = \Illuminate\Support\Str::slug($field['name'], '_');
                                        $inputType = 'text';
                                        if($field['type'] == 'number') $inputType = 'number';
                                        if($field['type'] == 'date') $inputType = 'date';
                                    @endphp
                                    <div class="{{ $inputType === 'text' ? 'md:col-span-2' : '' }}">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">{{ $field['name'] }}</label>
                                        <input type="{{ $inputType }}" name="{{ $fieldKey }}" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm" placeholder="Masukkan {{ $field['name'] }}">
                                    </div>
                                @endforeach
                            @else
                                <div class="md:col-span-2 p-4 bg-yellow-50 text-yellow-700 rounded-xl border border-yellow-200 text-sm font-medium">
                                    Form/Project ini belum memiliki konfigurasi field data.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-6">Data Sistem</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            @if($hasAgeCalcOption)
                            <div class="md:col-span-2 p-5 bg-purple-50/50 border border-purple-100 rounded-2xl">
                                <label class="block text-sm font-semibold text-purple-900 mb-2">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" required class="block w-full md:w-1/2 px-4 py-3 bg-white border border-purple-200 rounded-xl focus:ring-purple-500 focus:border-purple-500 transition shadow-sm">
                            </div>
                            @endif

                            @if($hasPhotoOption)
                            <div class="md:col-span-2 p-6 bg-blue-50/50 border border-blue-100 rounded-2xl">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                                    <div>
                                        <label class="block text-sm font-semibold text-blue-900">Upload Foto (Kamera / Galeri)</label>
                                        <p class="text-xs text-gray-500 mt-1">Sistem akan otomatis mengompresi gambar sebelum dikirim ke Cloudinary.</p>
                                    </div>
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <input type="file" name="album[]" accept="image/*" multiple class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer border border-gray-200 rounded-xl bg-white shadow-sm">
                                </div>
                            </div>
                            @endif

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Status Input</label>
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

                    <div class="pt-4 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="inline-flex items-center justify-center px-8 py-3.5 font-bold text-white transition-all duration-300 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl hover:from-emerald-400 hover:to-teal-500 shadow-[0_8px_30px_rgb(16,185,129,0.3)] hover:shadow-[0_8px_30px_rgb(16,185,129,0.5)] hover:-translate-y-1">
                            Simpan Data Responden
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
