<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <a href="{{ route('projects.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 flex items-center transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Kembali
                    </a>
                </div>
                <h2 class="font-black text-3xl text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-500 tracking-tight">
                    {{ $project->nama_project }}
                </h2>
                <p class="text-sm text-gray-500 mt-1 font-medium">{{ $project->deskripsi ?? 'Tidak ada deskripsi project.' }}</p>
            </div>

            <a href="{{ route('respondents.create', ['project_id' => $project->id]) }}" class="group relative inline-flex items-center justify-center px-6 py-3 font-bold text-white transition-all duration-300 bg-gradient-to-r from-emerald-500 via-emerald-600 to-teal-600 rounded-xl hover:from-emerald-400 hover:via-emerald-500 hover:to-teal-500 shadow-[0_8px_30px_rgb(16,185,129,0.3)] hover:shadow-[0_8px_30px_rgb(16,185,129,0.5)] hover:-translate-y-1">
                <svg class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Input Responden
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="p-4 bg-blue-50 text-blue-600 rounded-xl"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg></div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Total Responden</p>
                        <h3 class="text-2xl font-black text-gray-900">{{ $respondents->count() }}</h3>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-xl"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                    <div>
                        <p class="text-sm font-semibold text-gray-500">Telah Diinput</p>
                        <h3 class="text-2xl font-black text-gray-900">{{ $respondents->where('status', 'sudah_diinput')->count() }}</h3>
                    </div>
                </div>
            </div>

</div>

            <!-- Panel Filter & Pencarian -->
            <div class="bg-white/60 backdrop-blur-md rounded-3xl p-6 shadow-sm border border-gray-100 mb-8">
                <form action="{{ route('projects.show', $project->id) }}" method="GET" class="space-y-4">

                    <!-- Baris 1: Search & Status -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2 relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama, NIK, atau Alamat..." class="block w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm transition">
                        </div>
                        <div>
                            <select name="status" class="block w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 shadow-sm text-sm transition text-gray-600">
                                <option value="">Semua Status Input</option>
                                <option value="belum_diinput" {{ request('status') == 'belum_diinput' ? 'selected' : '' }}>Belum Diinput</option>
                                <option value="sudah_diinput" {{ request('status') == 'sudah_diinput' ? 'selected' : '' }}>Sudah Diinput</option>
                            </select>
                        </div>
                    </div>

                    <!-- Baris 2: Rentang Usia & Tanggal -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Rentang Usia -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Usia Min (Thn)</label>
                            <input type="number" name="age_min" value="{{ request('age_min') }}" min="0" placeholder="Cth: 17" class="block w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-indigo-500 text-sm shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Usia Max (Thn)</label>
                            <input type="number" name="age_max" value="{{ request('age_max') }}" min="0" placeholder="Cth: 60" class="block w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-indigo-500 text-sm shadow-sm">
                        </div>

                        <!-- Tanggal Input -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tgl Input (Dari)</label>
                            <input type="date" name="date_start" value="{{ request('date_start') }}" class="block w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-indigo-500 text-sm shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tgl Input (Sampai)</label>
                            <input type="date" name="date_end" value="{{ request('date_end') }}" class="block w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl focus:ring-indigo-500 text-sm shadow-sm">
                        </div>
                    </div>

                    <!-- Baris 3: Tombol Aksi -->
                    <div class="flex justify-end gap-3 pt-2">
                        @if(request()->anyFilled(['search', 'status', 'age_min', 'age_max', 'date_start', 'date_end']))
                            <a href="{{ route('projects.show', $project->id) }}" class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-gray-600 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition shadow-sm">
                                Reset Filter
                            </a>
                        @endif
                        <button type="submit" class="inline-flex items-center px-6 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-[0_4px_14px_0_rgb(79,70,229,0.39)] transition hover:-translate-y-0.5">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Tabel Data Responden -->
            <div class="bg-white/80 backdrop-blur-xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 sm:rounded-3xl">
                <div class="overflow-x-auto p-2">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="py-5 px-6 font-bold text-xs text-gray-400 uppercase tracking-widest border-b border-gray-100/50">NIK & Nama</th>
                                <th class="py-5 px-6 font-bold text-xs text-gray-400 uppercase tracking-widest border-b border-gray-100/50">TTL & Umur</th>
                                <th class="py-5 px-6 font-bold text-xs text-gray-400 uppercase tracking-widest border-b border-gray-100/50">Alamat</th>
                                <th class="py-5 px-6 font-bold text-xs text-gray-400 uppercase tracking-widest border-b border-gray-100/50 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50/50">
                            @forelse ($respondents as $resp)
                                <tr class="group hover:bg-slate-50/80 transition-all duration-300 rounded-2xl">
                                    <td class="py-5 px-6">
                                        <div class="font-semibold text-indigo-600 font-mono text-xs mb-1">{{ $resp->nik }}</div>
                                        <div class="font-extrabold text-gray-900 group-hover:text-emerald-600 transition-colors">{{ $resp->nama }}</div>
                                    </td>
                                    <td class="py-5 px-6">
                                        <div class="text-sm font-bold text-gray-800">{{ $resp->umur }} Tahun</div>
                                        <div class="text-xs text-gray-500">{{ $resp->tempat_lahir }}, {{ $resp->tanggal_lahir->format('d M Y') }}</div>
                                    </td>
                                    <td class="py-5 px-6 text-sm text-gray-500">
                                        <span class="line-clamp-2">{{ $resp->alamat }}</span>
                                    </td>
                                    <td class="py-5 px-6 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('respondents.album', $resp->id) }}" class="text-xs font-bold text-teal-600 hover:text-white bg-teal-50 hover:bg-teal-500 px-3 py-2 rounded-lg transition shadow-sm flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                Album ({{ is_array($resp->album) ? count($resp->album) : 0 }})
                                            </a>

                                            <a href="{{ route('respondents.edit', $resp->id) }}" class="text-xs font-bold text-indigo-600 hover:text-white bg-indigo-50 hover:bg-indigo-500 px-3 py-2 rounded-lg transition shadow-sm">
                                                Edit
                                            </a>

                                            <form action="{{ route('respondents.destroy', $resp->id) }}" method="POST" onsubmit="return confirm('Hapus responden dan seluruh foto albumnya di Cloudinary?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-rose-500 hover:text-white bg-rose-50 hover:bg-rose-500 rounded-lg transition shadow-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
@empty
                                <tr>
                                    <td colspan="4" class="py-16 px-6 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 shadow-inner border border-gray-100">
                                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                            </div>
                                            <h3 class="text-lg font-extrabold text-gray-900">Data Tidak Ditemukan</h3>
                                            <p class="mt-1 text-sm text-gray-500">
                                                @if(request()->anyFilled(['search', 'status', 'age_min', 'age_max', 'date_start', 'date_end']))
                                                    Tidak ada responden yang cocok dengan kriteria filter Anda. Coba sesuaikan ulang.
                                                @else
                                                    Mulai input data responden pertama untuk project ini.
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
