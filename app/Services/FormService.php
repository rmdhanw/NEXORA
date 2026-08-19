<?php

namespace App\Services;

use App\Models\Form;
use App\Models\Project;
use Illuminate\Support\Str;

class FormService
{
    /**
     * Create a new form for a project
     */
    public function createForm(Project $project, array $data): Form
    {
        $slug = Str::slug($data['title']) . '-' . Str::random(5);

        return $project->forms()->create([
            'title' => $data['title'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'has_photo' => isset($data['has_photo']) ? (bool) $data['has_photo'] : true,
            'has_age_calc' => isset($data['has_age_calc']) ? (bool) $data['has_age_calc'] : true,
            'fields_schema' => $data['fields'] ?? [],
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : true,
        ]);
    }

    /**
     * Update an existing form
     */
    public function updateForm(Form $form, array $data): Form
    {
        $form->update([
            'title' => $data['title'] ?? $form->title,
            'description' => $data['description'] ?? $form->description,
            'has_photo' => isset($data['has_photo']) ? (bool) $data['has_photo'] : $form->has_photo,
            'has_age_calc' => isset($data['has_age_calc']) ? (bool) $data['has_age_calc'] : $form->has_age_calc,
            'fields_schema' => $data['fields'] ?? $form->fields_schema,
            'is_active' => isset($data['is_active']) ? (bool) $data['is_active'] : $form->is_active,
        ]);

        return $form;
    }

    /**
     * Delete a form
     */
    public function deleteForm(Form $form): bool
    {
        return (bool) $form->delete();
    }
}
