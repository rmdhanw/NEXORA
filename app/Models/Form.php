<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'slug',
        'description',
        'has_photo',
        'has_age_calc',
        'fields_schema',
        'is_active',
    ];

    protected $casts = [
        'has_photo' => 'boolean',
        'has_age_calc' => 'boolean',
        'is_active' => 'boolean',
        'fields_schema' => 'array',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function respondents()
    {
        return $this->hasMany(Respondent::class);
    }
}
