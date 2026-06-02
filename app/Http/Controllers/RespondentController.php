<?php

namespace App\Http\Controllers;

use App\Models\Respondent;
use Illuminate\Http\Request;
use Cloudinary\Cloudinary;

class RespondentController extends Controller
{
    // Menampilkan Form Create
    public function create(Request $request)
    {
        $projectId = $request->query('project_id');
        if (!$projectId) {
            abort(404, 'Project ID tidak ditemukan.');
        }
        return view('respondents.create', compact('projectId'));
    }

    // Menyimpan Data Baru (Beserta Album)
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'nik' => 'required|string|max:20',
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

        $coreFields = ['_token', 'project_id', 'nama', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'nik', 'album', 'keterangan', 'status'];
        $dynamicData = $request->except($coreFields);

        Respondent::create([
            'project_id' => $request->project_id,
            'nama' => $request->nama,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'nik' => $request->nik,
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
        $request->validate([
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string',
            'nik' => 'required|string|max:20',
            'album.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'keterangan' => 'nullable|string',
            'status' => 'required|in:belum_diinput,sudah_diinput',
        ]);

        $albumUrls = is_array($respondent->album) ? $respondent->album : [];

        if ($request->hasFile('album')) {
            $cloudinary = new Cloudinary(env('CLOUDINARY_URL'));

            // Hapus file lama di Cloudinary (Dengan sistem pengaman Try-Catch)
            if (!empty($albumUrls)) {
                foreach ($albumUrls as $url) {
                    $publicId = $this->getCloudinaryPublicId($url);
                    if ($publicId) {
                        try {
                            $cloudinary->uploadApi()->destroy($publicId);
                        } catch (\Exception $e) {
                            // \Log::warning('Gagal hapus foto lama di Cloudinary: ' . $e->getMessage());
                        }
                    }
                }
            }

            // Upload foto-foto baru
            $albumUrls = [];
            foreach ($request->file('album') as $file) {
                $uploadedFile = $cloudinary->uploadApi()->upload($file->getRealPath(), [
                    'folder' => 'nexora_respondents',
                ]);
                $albumUrls[] = $uploadedFile['secure_url'];
            }
        }

        $coreFields = ['_token', '_method', 'nama', 'tempat_lahir', 'tanggal_lahir', 'alamat', 'nik', 'album', 'keterangan', 'status'];
        $dynamicData = $request->except($coreFields);

        $respondent->fill([
            'nama' => $request->nama,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'nik' => $request->nik,
            'album' => !empty($albumUrls) ? $albumUrls : null,
            'keterangan' => $request->keterangan,
            'status' => $request->status,
            'data_tambahan' => !empty($dynamicData) ? $dynamicData : null,
        ])->save();

        return redirect()->route('projects.show', $respondent->project_id)->with('success', 'Data responden beserta album berhasil diperbarui!');
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
}
