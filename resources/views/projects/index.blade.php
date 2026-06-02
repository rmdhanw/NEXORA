<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="font-black text-3xl text-transparent bg-clip-text bg-gradient-to-r from-gray-900 to-gray-500 tracking-tight">
                    {{ __('Manajemen Project') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1 font-medium">Kelola dan pantau aktivitas survei dalam satu ruang kendali.</p>
            </div>
            <a href="{{ route('projects.create') }}" class="group relative inline-flex items-center justify-center px-6 py-3 font-bold text-white transition-all duration-300 bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 rounded-xl hover:from-blue-500 hover:via-indigo-500 hover:to-violet-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 shadow-[0_8px_30px_rgb(79,70,229,0.3)] hover:shadow-[0_8px_30px_rgb(79,70,229,0.5)] hover:-translate-y-1">
                <svg class="w-5 h-5 mr-2 transition-transform duration-300 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Project Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-8 overflow-hidden rounded-2xl bg-white border border-emerald-100 shadow-[0_8px_30px_rgb(34,197,94,0.12)] transform transition-all" role="alert">
                    <div class="flex items-center p-4">
                        <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-bold text-emerald-800">Sistem Diperbarui!</h3>
                            <div class="mt-1 text-sm text-emerald-600">{{ session('success') }}</div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white/80 backdrop-blur-xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 sm:rounded-3xl relative">
                <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-blue-50 blur-3xl opacity-60 pointer-events-none"></div>

                <div class="overflow-x-auto relative z-10 p-2">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="py-5 px-6 font-bold text-xs text-gray-400 uppercase tracking-widest border-b border-gray-100/50">Nama Project</th>
                                <th class="py-5 px-6 font-bold text-xs text-gray-400 uppercase tracking-widest border-b border-gray-100/50">Status</th>
                                <th class="py-5 px-6 font-bold text-xs text-gray-400 uppercase tracking-widest border-b border-gray-100/50">Deskripsi</th>
                                <th class="py-5 px-6 font-bold text-xs text-gray-400 uppercase tracking-widest border-b border-gray-100/50 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50/50">
                            @forelse ($projects as $project)
                                <tr class="group hover:bg-slate-50/80 transition-all duration-300 ease-in-out rounded-2xl">
                                    <td class="py-5 px-6 relative">
                                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 rounded-r-md opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                                        <a href="{{ route('projects.show', $project->id) }}" class="font-extrabold text-gray-900 text-base group-hover:text-indigo-600 transition-colors block">{{ $project->nama_project }}</a>
                                        <div class="text-xs text-gray-400 mt-1 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $project->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="py-5 px-6">
                                        @if($project->status === 'completed')
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-green-50 text-green-700 border border-green-200 shadow-sm">
                                                <span class="w-2 h-2 mr-2 rounded-full bg-green-500"></span>
                                                COMPLETED
                                            </span>
                                        @elseif($project->status === 'on_progress')
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-sm">
                                                <span class="relative flex w-2 h-2 mr-2">
                                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                                  <span class="relative inline-flex rounded-full w-2 h-2 bg-indigo-500"></span>
                                                </span>
                                                ON PROGRESS
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold bg-gray-50 text-gray-700 border border-gray-200 shadow-sm">
                                                <span class="w-2 h-2 mr-2 rounded-full bg-gray-400"></span>
                                                PENDING
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-5 px-6 text-sm text-gray-500 leading-relaxed">
                                        {{ $project->deskripsi ?? 'Tidak ada deskripsi.' }}
                                    </td>
                                    <td class="py-5 px-6 text-right">
                                        <div class="flex items-center justify-end gap-2 opacity-70 group-hover:opacity-100 transition-opacity duration-300">
                                            <a href="{{ route('projects.edit', $project->id) }}" class="p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-600 hover:text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow-md" title="Edit Data">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Tindakan ini permanen. Hapus project survei ini?');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-rose-600 bg-rose-50 hover:bg-rose-600 hover:text-white rounded-lg transition-all duration-200 shadow-sm hover:shadow-md" title="Hapus Data">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-20 px-6 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mb-6 shadow-inner border border-indigo-100">
                                                <svg class="w-12 h-12 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                            </div>
                                            <h3 class="text-xl font-extrabold text-gray-900 tracking-tight">Ruang Kerja Kosong</h3>
                                            <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">Sistem NEXORA siap memproses data. Mulai inisiasi project pertama Anda sekarang.</p>
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
