<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class ProjectService
{
    protected FormService $formService;

    public function __construct(FormService $formService)
    {
        $this->formService = $formService;
    }

    /**
     * Get all projects for a given user
     */
    public function getUserProjects(int $userId): Collection
    {
        return Project::where('user_id', $userId)->latest()->get();
    }

    /**
     * Create project (and optionally create default form if fields are provided)
     */
    public function createProject(User $user, array $data): Project
    {
        $project = Project::create([
            'user_id' => $user->id,
            'nama_project' => $data['nama_project'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'has_photo' => isset($data['has_photo']) ? (bool) $data['has_photo'] : true,
            'has_age_calc' => isset($data['has_age_calc']) ? (bool) $data['has_age_calc'] : true,
            'master_fields' => $data['fields'] ?? [],
        ]);

        // Auto-create initial default Form for backward compatibility
        $this->formService->createForm($project, [
            'title' => 'Form Utama ' . $project->nama_project,
            'description' => 'Form standar untuk project ' . $project->nama_project,
            'has_photo' => $project->has_photo,
            'has_age_calc' => $project->has_age_calc,
            'fields' => $data['fields'] ?? [],
        ]);

        return $project;
    }

    /**
     * Update project metadata
     */
    public function updateProject(Project $project, array $data): Project
    {
        $project->update([
            'nama_project' => $data['nama_project'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'status' => $data['status'] ?? $project->status,
        ]);

        return $project;
    }

    /**
     * Delete project
     */
    public function deleteProject(Project $project): bool
    {
        return (bool) Project::destroy($project->id);
    }
}
