<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Respondent;
use App\Models\Form;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class RespondentService
{
    protected CloudinaryService $cloudinaryService;

    public function __construct(CloudinaryService $cloudinaryService)
    {
        $this->cloudinaryService = $cloudinaryService;
    }

    /**
     * Get filtered respondents for a project or form
     */
    public function getFilteredRespondents(Project $project, array $filters = [], ?Form $form = null)
    {
        $query = $project->respondents()->latest();

        if ($form) {
            $query->where('form_id', $form->id);
        }

        // 1. Search filter across JSON dynamic data
        if (!empty($filters['search'])) {
            $search = strtolower($filters['search']);
            $query->whereRaw('LOWER(data_tambahan) LIKE ?', ["%{$search}%"]);
        }

        // 2. Status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // 3. Date range filter
        if (!empty($filters['date_start']) && !empty($filters['date_end'])) {
            $query->whereBetween('created_at', [
                $filters['date_start'] . ' 00:00:00',
                $filters['date_end'] . ' 23:59:59'
            ]);
        }

        // 4. Age range filter using native Eloquent JSON query syntax
        if (!empty($filters['age_min']) && !empty($filters['age_max'])) {
            $minDate = Carbon::now()->subYears((int)$filters['age_max'] + 1)->addDay()->toDateString();
            $maxDate = Carbon::now()->subYears((int)$filters['age_min'])->toDateString();

            $query->whereBetween('data_tambahan->tanggal_lahir', [$minDate, $maxDate]);
        }

        return $query->get();
    }

    /**
     * Store a new respondent
     */
    public function storeRespondent(array $data, array $files = []): Respondent
    {
        $albumUrls = [];
        if (!empty($files)) {
            $albumUrls = $this->cloudinaryService->uploadMultipleFiles($files);
        }

        $coreFields = ['_token', 'project_id', 'form_id', 'album', 'keterangan', 'status'];
        $dynamicData = array_diff_key($data, array_flip($coreFields));

        return Respondent::create([
            'project_id' => $data['project_id'],
            'form_id' => $data['form_id'] ?? null,
            'album' => !empty($albumUrls) ? $albumUrls : null,
            'keterangan' => $data['keterangan'] ?? null,
            'status' => $data['status'] ?? 'belum_diinput',
            'data_tambahan' => !empty($dynamicData) ? $dynamicData : null,
        ]);
    }

    /**
     * Update existing respondent
     */
    public function updateRespondent(Respondent $respondent, array $data, array $files = []): Respondent
    {
        $albumUrls = is_array($respondent->album) ? $respondent->album : [];

        if (!empty($files)) {
            // Delete old photos
            if (!empty($albumUrls)) {
                $this->cloudinaryService->deleteMultipleFiles($albumUrls);
            }
            // Upload new photos
            $albumUrls = $this->cloudinaryService->uploadMultipleFiles($files);
        }

        $coreFields = ['_token', '_method', 'album', 'keterangan', 'status'];
        $dynamicData = array_diff_key($data, array_flip($coreFields));

        $respondent->fill([
            'album' => !empty($albumUrls) ? $albumUrls : null,
            'keterangan' => $data['keterangan'] ?? $respondent->keterangan,
            'status' => $data['status'] ?? $respondent->status,
            'data_tambahan' => !empty($dynamicData) ? $dynamicData : null,
        ])->save();

        return $respondent;
    }

    /**
     * Delete single respondent
     */
    public function deleteRespondent(Respondent $respondent): bool
    {
        if (is_array($respondent->album) && !empty($respondent->album)) {
            $this->cloudinaryService->deleteMultipleFiles($respondent->album);
        }

        return (bool) Respondent::destroy($respondent->id);
    }

    /**
     * Bulk delete respondents
     */
    public function bulkDeleteRespondents(array $ids, int $userId): int
    {
        $respondents = Respondent::whereIn('id', $ids)->get();
        $deletedCount = 0;

        foreach ($respondents as $respondent) {
            if ($respondent->project && $respondent->project->user_id === $userId) {
                $this->deleteRespondent($respondent);
                $deletedCount++;
            }
        }

        return $deletedCount;
    }
}
