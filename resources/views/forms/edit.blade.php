<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('projects.show', $form->project_id) }}" class="p-2 bg-white rounded-full shadow-sm hover:bg-gray-50 transition">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <div>
                <h2 class="font-black text-2xl text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-500 tracking-tight">
                    Edit Master Form - {{ $form->title }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Perbarui definisi dan field dinamis pada form ini.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="formBuilder({{ json_encode($form->fields_schema ?? []) }})">
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
                <form action="{{ route('forms.update', $form->id) }}" method="POST" class="p-8">
                    @csrf
                    @method('PUT')

                    <div class="mb-8 border-b pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Form</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Judul Form</label>
                                <input type="text" name="title" value="{{ old('title', $form->title) }}" required class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi (Opsional)</label>
                                <textarea name="description" rows="2" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition shadow-sm">{{ old('description', $form->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8 border-b pb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Konfigurasi Fitur Bawaan</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-100 transition">
                                <input type="checkbox" name="has_photo" value="1" {{ $form->has_photo ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                <span class="ml-3 font-semibold text-sm text-gray-800">Aktifkan Upload Foto Album (Cloudinary)</span>
                            </label>
                            <label class="flex items-center p-4 bg-gray-50 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-100 transition">
                                <input type="checkbox" name="has_age_calc" value="1" {{ $form->has_age_calc ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                                <span class="ml-3 font-semibold text-sm text-gray-800">Aktifkan Hitung Umur (Tanggal Lahir)</span>
                            </label>
                        </div>
                    </div>

                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900">Builder Field Dinamis</h3>
                            <button type="button" @click="addField()" class="px-4 py-2 bg-indigo-50 text-indigo-600 font-bold text-sm rounded-xl hover:bg-indigo-100 transition">
                                + Tambah Field
                            </button>
                        </div>

                        <div class="space-y-4">
                            <template x-for="(field, index) in fields" :key="index">
                                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-200">
                                    <div class="flex-1">
                                        <input type="text" :name="`fields[${index}][name]`" x-model="field.name" placeholder="Nama Label" required class="block w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm">
                                    </div>
                                    <div class="w-48">
                                        <select :name="`fields[${index}][type]`" x-model="field.type" class="block w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm">
                                            <option value="text">Teks</option>
                                            <option value="number">Angka</option>
                                            <option value="date">Tanggal</option>
                                        </select>
                                    </div>
                                    <button type="button" @click="removeField(index)" class="p-2.5 text-rose-500 hover:bg-rose-50 rounded-xl transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                        <form action="{{ route('forms.destroy', $form->id) }}" method="POST" onsubmit="return confirm('Hapus master form ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-5 py-2.5 text-sm font-bold text-rose-600 bg-rose-50 rounded-xl hover:bg-rose-100 transition">
                                Hapus Form
                            </button>
                        </form>
                        <button type="submit" class="px-8 py-3.5 font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-md transition">
                            Perbarui Master Form
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function formBuilder(initialFields) {
            return {
                fields: initialFields.length ? initialFields : [{ name: '', type: 'text' }],
                addField() {
                    this.fields.push({ name: '', type: 'text' });
                },
                removeField(index) {
                    if (this.fields.length > 1) {
                        this.fields.splice(index, 1);
                    }
                }
            }
        }
    </script>
</x-app-layout>
