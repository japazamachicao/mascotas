<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthAnalysis extends Model
{
    use HasFactory;

    protected $fillable = [
        'pet_id',
        'user_id',
        'analysis_type',
        'image_path',
        'ai_response',
        'findings',
        'requires_attention',
        'recommendations',
        'confidence_score',
    ];

    protected $casts = [
        'ai_response' => 'array',
        'findings' => 'array',
        'requires_attention' => 'boolean',
        'confidence_score' => 'float',
    ];

    public function pet(): BelongsTo
    {
        return $this->belongsTo(Pet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getAnalysisTypeNameAttribute(): string
    {
        switch ($this->analysis_type) {
            case 'feces':
                return 'Análisis de Heces';
            case 'urine':
                return 'Análisis de Orina';
            case 'skin':
                return 'Análisis de Piel/Lengua';
            default:
                return 'Análisis de Salud';
        }
    }
}
