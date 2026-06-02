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
        $request->validate([
            'nama_project' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => 'required|in:pending,on_progress,completed',
        ]);

        Project::create([
            'user_id' => Auth::id(),
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project berhasil ditambahkan!');
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

        $project->fill([
            'nama_project' => $request->nama_project,
            'deskripsi' => $request->deskripsi,
            'status' => $request->status,
        ])->save();

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
        // Keamanan: Pastikan hanya pembuat project yang bisa melihat
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        // Mulai query dasar
        $query = $project->respondents()->latest();

        // 1. Filter Pencarian (Nama, NIK, Alamat)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        // 2. Filter Status
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

        // 4. Filter Rentang Usia (Konversi ke Tanggal Lahir)
        if ($request->filled('age_min') && $request->filled('age_max')) {
            // Logika: Usia 30 tahun berarti lahir maksimal 30 tahun lalu.
            // Usia 20 tahun berarti lahir minimal 20 tahun lalu.
            $minDate = Carbon::now()->subYears($request->age_max + 1)->addDay()->toDateString();
            $maxDate = Carbon::now()->subYears($request->age_min)->toDateString();

            $query->whereBetween('tanggal_lahir', [$minDate, $maxDate]);
        }

        // Eksekusi query
        $respondents = $query->get();

        return view('projects.show', compact('project', 'respondents'));
    }
}
