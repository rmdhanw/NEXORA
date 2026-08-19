<?php

namespace App\Services;

use Cloudinary\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected ?Cloudinary $cloudinary = null;
    protected string $folder = 'nexora_respondents';

    public function __construct()
    {
        $cloudinaryUrl = env('CLOUDINARY_URL');
        if ($cloudinaryUrl) {
            $this->cloudinary = new Cloudinary($cloudinaryUrl);
        }
    }

    /**
     * Upload single file to Cloudinary
     */
    public function uploadFile(UploadedFile $file): ?string
    {
        if (!$this->cloudinary) {
            return null;
        }

        try {
            $uploadedFile = $this->cloudinary->uploadApi()->upload($file->getRealPath(), [
                'folder' => $this->folder,
            ]);
            return $uploadedFile['secure_url'] ?? null;
        } catch (\Exception $e) {
            Log::error('Cloudinary Upload Failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload multiple files to Cloudinary
     *
     * @param UploadedFile[] $files
     * @return string[] Array of secure URLs
     */
    public function uploadMultipleFiles(array $files): array
    {
        $urls = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $url = $this->uploadFile($file);
                if ($url) {
                    $urls[] = $url;
                }
            }
        }
        return $urls;
    }

    /**
     * Delete multiple files from Cloudinary by URLs
     */
    public function deleteMultipleFiles(array $urls): void
    {
        if (!$this->cloudinary || empty($urls)) {
            return;
        }

        /** @var \Cloudinary\Api\Upload\UploadApi $uploadApi */
        $uploadApi = $this->cloudinary->uploadApi();

        foreach ($urls as $url) {
            $publicId = $this->extractPublicId($url);
            if ($publicId) {
                try {
                    $uploadApi->destroy($publicId);
                } catch (\Exception $e) {
                    Log::warning('Cloudinary Delete Failed: ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Extract public ID from Cloudinary URL
     */
    public function extractPublicId(string $url): ?string
    {
        $pattern = '/' . preg_quote($this->folder, '/') . '\/(.*?)\.[a-zA-Z0-9]+$/';
        if (preg_match($pattern, $url, $matches)) {
            return $this->folder . '/' . $matches[1];
        }
        return null;
    }
}
