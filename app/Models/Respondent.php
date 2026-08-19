<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Respondent extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id', 'form_id', 'nama', 'tempat_lahir', 'tanggal_lahir', 'alamat',
        'nik', 'album', 'keterangan', 'status', 'data_tambahan',
    ];

    protected function casts(): array
    {
        return [
            'data_tambahan' => 'array',
            'album' => 'array', // Otomatis mengubah JSON Album menjadi Array
            'tanggal_lahir' => 'date', // Mengubah teks tanggal menjadi objek Carbon
        ];
    }

    // Fitur Canggih: Accessor untuk menghitung umur otomatis
    public function getUmurAttribute()
    {
        return $this->tanggal_lahir ? Carbon::parse($this->tanggal_lahir)->age : 0;
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
