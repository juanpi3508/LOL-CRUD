<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Champion extends Model
{
    public const ROLES = [
        'Assassin' => 'Asesino',
        'Fighter' => 'Luchador',
        'Mage' => 'Mago',
        'Marksman' => 'Tirador',
        'Support' => 'Soporte',
        'Tank' => 'Tanque',
    ];

    public const DIFFICULTIES = ['Baja', 'Media', 'Alta'];

    public const RESOURCE_TYPES = ['Maná', 'Energía', 'Sin maná', 'Furia', 'Vida'];

    protected $fillable = [
        'name',
        'title',
        'role',
        'resource_type',
        'difficulty',
        'lore',
        'image_url',
    ];

    public function getDisplayImageAttribute(): string
    {
        return $this->image_url ?: 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/'.rawurlencode($this->name).'_0.jpg';
    }

    /**
     * Aspectos o skins pertenecientes a este campeón.
     */
    public function skins(): HasMany
    {
        return $this->hasMany(Skin::class);
    }
}
