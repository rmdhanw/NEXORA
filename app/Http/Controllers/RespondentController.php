<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRespondentRequest;
use App\Models\Form;
use App\Models\Project;
use App\Models\Respondent;
use App\Services\RespondentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RespondentController extends Controller
{
    protected RespondentService $respondentService;

    public function __construct(RespondentService $respondentService)
    {
        $this->respondentService = $respondentService;
    }

    public function create(Request $request)
    {
        $projectId = $request->query('project_id');
        $formId = $request->query('form_id');

        if (!$projectId) {
            abort(404, 'Project ID tidak ditemukan.');
        }

        $project = Project::where('user_id', Auth::id())->findOrFail($projectId);
        $form = $formId ? Form::where('project_id', $projectId)->find($formId) : $project->forms()->first();

        return view('respondents.create', compact('project', 'projectId', 'form'));
    }

    public function store(StoreRespondentRequest $request)
    {
        $files = $request->hasFile('album') ? $request->file('album') : [];
        $this->respondentService->storeRespondent($request->all(), $files);

        return redirect()->back()->with('success', 'Data responden dan album berhasil disimpan!');
    }

    public function edit(Respondent $respondent)
    {
        if ($respondent->project->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        return view('respondents.edit', compact('respondent'));
    }

    public function update(Request $request, Respondent $respondent)
    {
        if ($respondent->project->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'album.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:belum_diinput,sudah_diinput',
        ]);

        $files = $request->hasFile('album') ? $request->file('album') : [];
        $this->respondentService->updateRespondent($respondent, $request->all(), $files);

        return redirect()->route('projects.show', $respondent->project_id)
            ->with('success', 'Data responden berhasil diperbarui!');
    }

    public function destroy(Respondent $respondent)
    {
        if ($respondent->project->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $projectId = $respondent->project_id;
        $this->respondentService->deleteRespondent($respondent);

        return redirect()->route('projects.show', $projectId)
            ->with('success', 'Data responden dan seluruh album fotonya berhasil dihapus permanen!');
    }

    public function album(Respondent $respondent)
    {
        if ($respondent->project->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        return view('respondents.album', compact('respondent'));
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:respondents,id',
        ]);

        $deletedCount = $this->respondentService->bulkDeleteRespondents(
            $request->input('ids'),
            Auth::id()
        );

        return redirect()->back()->with('success', "{$deletedCount} data responden dan fotonya berhasil dihapus secara permanen!");
    }
}
