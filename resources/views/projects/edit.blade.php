<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('projects.update', $project->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Project</label>
                        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" type="text" name="nama_project" required value="{{ old('nama_project', $project->nama_project) }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                        <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="deskripsi" rows="4">{{ old('deskripsi', $project->deskripsi) }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                        <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" name="status">
                            <option value="pending" {{ $project->status == 'pending' ? 'selected' : '' }}>PENDING</option>
                            <option value="on_progress" {{ $project->status == 'on_progress' ? 'selected' : '' }}>ON PROGRESS</option>
                            <option value="completed" {{ $project->status == 'completed' ? 'selected' : '' }}>COMPLETED</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-4">
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">
                            Perbarui Data
                        </button>
                        <a href="{{ route('projects.index') }}" class="text-sm text-gray-600 hover:text-gray-900">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
