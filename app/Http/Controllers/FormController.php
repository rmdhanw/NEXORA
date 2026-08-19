<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFormRequest;
use App\Models\Form;
use App\Models\Project;
use App\Services\FormService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormController extends Controller
{
    protected FormService $formService;

    public function __construct(FormService $formService)
    {
        $this->formService = $formService;
    }

    public function create(Request $request)
    {
        $projectId = $request->query('project_id');
        $project = Project::where('user_id', Auth::id())->findOrFail($projectId);

        return view('forms.create', compact('project'));
    }

    public function store(StoreFormRequest $request)
    {
        $project = Project::where('user_id', Auth::id())->findOrFail($request->project_id);

        $form = $this->formService->createForm($project, $request->validated());

        return redirect()->route('projects.show', $project->id)
            ->with('success', "Master Form '{$form->title}' berhasil dibuat!");
    }

    public function edit(Form $form)
    {
        if ($form->project->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        return view('forms.edit', compact('form'));
    }

    public function update(Request $request, Form $form)
    {
        if ($form->project->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'has_photo' => 'nullable',
            'has_age_calc' => 'nullable',
            'fields' => 'nullable|array',
            'fields.*.name' => 'required_with:fields|string',
            'fields.*.type' => 'required_with:fields|string|in:text,number,date',
        ]);

        $data = $request->all();
        $data['has_photo'] = $request->has('has_photo');
        $data['has_age_calc'] = $request->has('has_age_calc');

        $this->formService->updateForm($form, $data);

        return redirect()->route('projects.show', $form->project_id)
            ->with('success', "Form '{$form->title}' berhasil diperbarui!");
    }

    public function destroy(Form $form)
    {
        if ($form->project->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $projectId = $form->project_id;
        $this->formService->deleteForm($form);

        return redirect()->route('projects.show', $projectId)
            ->with('success', 'Master Form berhasil dihapus!');
    }
}
