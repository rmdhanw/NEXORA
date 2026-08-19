<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Models\Project;
use App\Services\ProjectService;
use App\Services\RespondentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    protected ProjectService $projectService;
    protected RespondentService $respondentService;

    public function __construct(ProjectService $projectService, RespondentService $respondentService)
    {
        $this->projectService = $projectService;
        $this->respondentService = $respondentService;
    }

    public function index()
    {
        $projects = $this->projectService->getUserProjects(Auth::id());
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        return view('projects.create');
    }

    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectService->createProject(Auth::user(), $request->validated());

        return redirect()->route('projects.index')
            ->with('success', "Project '{$project->nama_project}' dan Master Data berhasil dikonfigurasi!");
    }

    public function show(Request $request, Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $filters = $request->only(['search', 'status', 'date_start', 'date_end', 'age_min', 'age_max']);
        $respondents = $this->respondentService->getFilteredRespondents($project, $filters);
        $forms = $project->forms()->latest()->get();

        return view('projects.show', compact('project', 'respondents', 'forms'));
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

        $this->projectService->updateProject($project, $request->all());

        return redirect()->route('projects.index')->with('success', 'Project berhasil diperbarui!');
    }

    public function destroy(Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $this->projectService->deleteProject($project);

        return redirect()->route('projects.index')->with('success', 'Project berhasil dihapus!');
    }
}
