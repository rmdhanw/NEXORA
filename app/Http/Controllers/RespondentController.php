<?php

namespace App\Http\Controllers;

use App\Models\Respondent;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;
use Illuminate\Support\Facades\Auth; // Pastikan ini ada di atas

class RespondentController extends Controller
{
    // Menampilkan Form Create
    public function create(Request $request)
    {
        $projectId = $request->query('project_id');
        if (!$projectId) {
            abort(404, 'Project ID tidak ditemukan.');
        }

        // Panggil data project agar master_fields-nya bisa dibaca di View
        $project = \App\Models\Project::findOrFail($projectId);

        return view('respondents.create', compact('project', 'projectId'));
    }

    // Menyimpan Data Baru (Beserta Album)
    public function store(Request $request)
    {
        // 1. Hapus 'tanggal_lahir' dari validasi
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'album.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:belum_diinput,sudah_diinput',
        ]);

        $albumUrls = [];
        if ($request->hasFile('album')) {
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
            foreach ($request->file('album') as $file) {
                $uploadedFile = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                    'folder' => 'nexora_respondents',
                ]);
                $albumUrls[] = $uploadedFile['secure_url'];
            }
        }

        // 2. Hapus 'tanggal_lahir' dari coreFields
        $coreFields = ['_token', 'project_id', 'album', 'keterangan', 'status'];
        $dynamicData = $request->except($coreFields);

        // 3. Hapus 'tanggal_lahir' dari proses create()
        Respondent::create([
            'project_id' => $request->project_id,
            'album' => !empty($albumUrls) ? $albumUrls : null,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'data_tambahan' => !empty($dynamicData) ? $dynamicData : null,
        ]);

        return redirect()->back()->with('success', 'Data responden dan album berhasil disimpan!');
    }

    // Menampilkan Form Edit
    public function edit(Respondent $respondent)
    {
        return view('respondents.edit', compact('respondent'));
    }

    // Memperbarui Data (Beserta Modifikasi Album)
   public function update(Request $request, Respondent $respondent)
    {
        // 1. Hapus 'tanggal_lahir' dari validasi
        $request->validate([
            'album.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:belum_diinput,sudah_diinput',
        ]);

        $albumUrls = is_array($respondent->album) ? $respondent->album : [];

        if ($request->hasFile('album')) {
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));

            if (!empty($albumUrls)) {
                foreach ($albumUrls as $url) {
                    $publicId = $this->getCloudinaryPublicId($url);
                    if ($publicId) {
                        try {
                            $cloudinary->uploadApi()->destroy($publicId);
                        } catch (\Exception $e) {}
                    }
                }
            }

            $albumUrls = [];
            foreach ($request->file('album') as $file) {
                $uploadedFile = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                    'folder' => 'nexora_respondents',
                ]);
                $albumUrls[] = $uploadedFile['secure_url'];
            }
        }

        // 2. Hapus 'tanggal_lahir' dari coreFields agar ia ditangkap sebagai $dynamicData (JSON)
        $coreFields = ['_token', '_method', 'album', 'keterangan', 'status'];
        $dynamicData = $request->except($coreFields);

        // 3. Hapus 'tanggal_lahir' dari proses fill()
        $respondent->fill([
            'album' => !empty($albumUrls) ? $albumUrls : null,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'data_tambahan' => !empty($dynamicData) ? $dynamicData : null,
        ])->save();

        return redirect()->route('projects.show', $respondent->project_id)->with('success', 'Data responden berhasil diperbarui!');
    }

  // Menghapus Data Permanen
    public function destroy(Respondent $respondent)
    {
        $projectId = $respondent->project_id;

        // 1. Hapus Album di Cloudinary dengan perlindungan Try-Catch
        if (is_array($respondent->album) && !empty($respondent->album)) {

            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));

            // Memberikan petunjuk kepada Intelephense agar tidak bingung (DocBlock)
            /** @var \Cloudinary\Api\Upload\UploadApi $uploadApi */
            $uploadApi = $cloudinary->uploadApi();

            foreach ($respondent->album as $url) {
                $publicId = $this->getCloudinaryPublicId($url);
                if ($publicId) {
                    try {
                        $uploadApi->destroy($publicId);
                    } catch (\Exception $e) {
                        // \Log::warning('Cloudinary error diabaikan: ' . $e->getMessage());
                    }
                }
            }
        }

        // 2. Hapus data secara permanen di Database MySQL
        // Menggunakan metode statis destroy untuk menghindari False Positive Intelephense
        Respondent::destroy($respondent->id);

        return redirect()->route('projects.show', $projectId)->with('success', 'Data responden dan seluruh album fotonya berhasil dihapus permanen!');
    }

    // Menampilkan Halaman Galeri Album
    public function album(Respondent $respondent)
    {
        return view('respondents.album', compact('respondent'));
    }


// Menambahkan type-hint "string" pada parameter $url
    private function getCloudinaryPublicId(string $url)
    {
        $folder = 'nexora_respondents';
        $pattern = '/' . $folder . '\/(.*?)\.[a-zA-Z0-9]+$/';

        if (preg_match($pattern, $url, $matches)) {
            return $folder . '/' . $matches[1];
        }

        return null;
    }
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:respondents,id',
        ]);

// Berikan 4 parameter secara eksplisit ('and' dan false) untuk memuaskan Intelephense
        $respondents = Respondent::query()->whereIn('id', $request->input('ids'), 'and', false)->get();
        $deletedCount = 0;

        // Inisialisasi Cloudinary sekali saja di luar loop agar proses hapus massal lebih cepat
        $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));
        /** @var \Cloudinary\Api\Upload\UploadApi $uploadApi */
        $uploadApi = $cloudinary->uploadApi();

        foreach ($respondents as $respondent) {
            // Keamanan: Pastikan responden dimiliki oleh project milik user yang sedang login
            if ($respondent->project->user_id === Auth::id()) {

                // Hapus semua foto dari Cloudinary dengan sistem pengaman Try-Catch
                if (is_array($respondent->album) && !empty($respondent->album)) {
                    foreach ($respondent->album as $fotoUrl) {
                        // Gunakan fungsi private getCloudinaryPublicId yang sudah Anda buat
                        $publicId = $this->getCloudinaryPublicId($fotoUrl);

                        if ($publicId) {
                            try {
                                $uploadApi->destroy($publicId);
                            } catch (\Exception $e) {
                                // Abaikan jika foto mungkin sudah terhapus sebelumnya di Cloudinary
                            }
                        }
                    }
                }

                // Hapus dari database menggunakan metode destroy() agar Intelephense tenang
                Respondent::destroy($respondent->id);
                $deletedCount++;
            }
        }

        return redirect()->back()->with('success', "$deletedCount data responden dan fotonya berhasil dihapus secara permanen!");
    }
}
