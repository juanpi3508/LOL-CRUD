<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skin extends Model
{
    public const TIERS = [
        'Clásica' => 'Clásica',
        'Épica' => 'Épica',
        'Legendaria' => 'Legendaria',
        'Mítica' => 'Mítica',
        'Definitiva' => 'Definitiva',
    ];

    protected $fillable = [
        'champion_id',
        'name',
        'splash_art_url',
        'tier',
        'price_rp',
        'is_default',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price_rp' => 'integer',
            'is_default' => 'boolean',
        ];
    }

    /**
     * Relación con el campeón al que pertenece la skin.
     */
    public function champion(): BelongsTo
    {
        return $this->belongsTo(Champion::class);
    }

    /**
     * Obtiene la URL de la imagen a mostrar con fallback.
     */
    public function getDisplayImageAttribute(): string
    {
        if (! empty($this->splash_art_url)) {
            return $this->splash_art_url;
        }

        if ($this->champion) {
            return $this->champion->display_image;
        }

        return 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Ahri_0.jpg';
    }

    /**
     * Obtiene las clases CSS del badge de rareza según el tier.
     */
    public function getTierColorAttribute(): string
    {
        return match ($this->tier) {
            'Épica' => 'text-cyan-400 border-cyan-500/50 bg-cyan-950/50',
            'Legendaria' => 'text-amber-400 border-amber-500/50 bg-amber-950/50',
            'Mítica' => 'text-purple-400 border-purple-500/50 bg-purple-950/50',
            'Definitiva' => 'text-orange-400 border-orange-500/50 bg-orange-950/50',
            default => 'text-gray-300 border-gray-600 bg-gray-900/60',
        };
    }
}
