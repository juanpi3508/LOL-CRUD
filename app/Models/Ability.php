<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ability extends Model
{
    public const SLOTS = [
        'P' => 'Pasiva',
        'Q' => 'Habilidad Q',
        'W' => 'Habilidad W',
        'E' => 'Habilidad E',
        'R' => 'Definitiva (R)',
    ];

    protected $fillable = [
        'champion_id',
        'slot',
        'name',
        'description',
        'icon_url',
        'cooldown',
        'cost',
    ];

    /**
     * Relación con el campeón al que pertenece la habilidad.
     */
    public function champion(): BelongsTo
    {
        return $this->belongsTo(Champion::class);
    }

    /**
     * Nombre legible del slot de la habilidad.
     */
    public function getSlotNameAttribute(): string
    {
        return self::SLOTS[$this->slot] ?? $this->slot;
    }

    /**
     * Icono oficial de la habilidad o fallback elegante.
     */
    public function getDisplayIconAttribute(): string
    {
        if (! empty($this->icon_url)) {
            return $this->icon_url;
        }

        return 'https://raw.communitydragon.org/latest/plugins/rcp-be-lol-game-data/global/default/v1/champion-icons/-1.png';
    }

    /**
     * Color del distintivo según la tecla asignada.
     */
    public function getSlotColorAttribute(): string
    {
        return match ($this->slot) {
            'P' => 'border-gray-500 bg-gray-900/80 text-gray-300',
            'Q' => 'border-[#0ac8b9] bg-[#0ac8b9]/10 text-[#0ac8b9]',
            'W' => 'border-emerald-500 bg-emerald-950/40 text-emerald-400',
            'E' => 'border-amber-500 bg-amber-950/40 text-amber-400',
            'R' => 'border-[#c89b3c] bg-[#c89b3c]/20 text-[#c89b3c] font-black shadow-[0_0_10px_rgba(200,155,60,0.3)]',
            default => 'border-gray-600 bg-gray-900 text-gray-400',
        };
    }
}
