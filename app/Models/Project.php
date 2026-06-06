<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    // Tambahkan 3 kolom baru ke fillable
    protected $fillable = [
        'user_id',
        'nama_project',
        'deskripsi',
        'status',
        'has_photo',
        'has_age_calc',
        'master_fields'
    ];

    // Beritahu Laravel untuk mengubah JSON menjadi Array secara otomatis
    protected $casts = [
        'has_photo' => 'boolean',
        'has_age_calc' => 'boolean',
        'master_fields' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function respondents()
    {
        return $this->hasMany(Respondent::class);
    }
}
