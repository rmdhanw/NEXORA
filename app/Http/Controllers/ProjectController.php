<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::query()->where('user_id', Auth::id())->latest()->get();
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

public function store(Request $request)
    {
        // Validasi input dasar dan array dinamis
        $request->validate([
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:pending,on_progress,completed',
            'has_photo' => 'nullable', // Berupa checkbox
            'has_age_calc' => 'nullable', // Berupa checkbox
            'fields' => 'nullable|array',
            'fields.*.name' => 'required_with:fields|string',
            'fields.*.type' => 'required_with:fields|string|in:text,number,date',
        ]);

        // Simpan ke database
        Project::create([
            'user_id' => Auth::id(),
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
            // Jika checkbox dicentang nilainya ada, jika tidak otomatis false
            'has_photo' => $request->has('has_photo'),
            'has_age_calc' => $request->has('has_age_calc'),
            // Simpan array fields langsung menjadi JSON (otomatis di-handle oleh model $casts)
            'master_fields' => $request->fields ?? [],
        ]);

        return redirect()->route('projects.index')->with('success', 'Project dan Master Data berhasil dikonfigurasi!');
    }
    public function edit(Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }
        return view('projects.edit', compact('project'));
    }
    public function update(Request $request, Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:pending,on_progress,completed',
        ]);
        $project->nama_project = $request->nama_project;
        $project->deskripsi = $request->deskripsi;
        $project->status = $request->status;
        $project->save();

        return redirect()->route('projects.index')->with('success', 'Project berhasil diperbarui!');
    }
    public function destroy(Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        Project::destroy($project->id);

        return redirect()->route('projects.index')->with('success', 'Project berhasil dihapus!');
    }
    public function show(Request $request, Project $project)
    {
        if ($project->user_id !== Auth::id()) abort(403, 'Akses ditolak.');

        $query = $project->respondents()->latest();

        // 1. Filter Pencarian (Mencari teks apapun di dalam JSON data_tambahan)
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            // Menggunakan fungsi LOWER dan LIKE agar pencarian fleksibel di dalam JSON
            $query->whereRaw('LOWER(data_tambahan) LIKE ?', ["%{$search}%"]);
        }

        // 2. Filter Status (Wajib Ada)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Filter Tanggal Input
        if ($request->filled('date_start') && $request->filled('date_end')) {
            $query->whereBetween('created_at', [
                $request->date_start . ' 00:00:00',
                $request->date_end . ' 23:59:59'
            ]);
        }

        // 4. Filter Rentang Usia (Mengekstrak tanggal_lahir dari dalam JSON)
        if ($request->filled('age_min') && $request->filled('age_max')) {
            $minDate = \Carbon\Carbon::now()->subYears($request->age_max + 1)->addDay()->toDateString();
            $maxDate = \Carbon\Carbon::now()->subYears($request->age_min)->toDateString();

            // Ekstrak key 'tanggal_lahir' dari objek JSON untuk difilter
            $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(data_tambahan, '$.tanggal_lahir')) BETWEEN ? AND ?", [$minDate, $maxDate]);
        }

        $respondents = $query->get();
        return view('projects.show', compact('project', 'respondents'));
    }
}
