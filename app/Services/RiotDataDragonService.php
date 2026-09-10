<?php

namespace App\Services;

use App\Models\Ability;
use App\Models\Champion;
use App\Models\Skin;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class RiotDataDragonService
{
    public const DEFAULT_VERSION = '14.5.1';

    public const LOCALE = 'es_ES';

    /**
     * Normaliza el nombre del campeón al formato de clave que utiliza Data Dragon de Riot.
     */
    public function normalizeChampionKey(string $name): string
    {
        $overrides = [
            'wukong' => 'MonkeyKing',
            'cho\'gath' => 'Chogath',
            'chogath' => 'Chogath',
            'kai\'sa' => 'Kaisa',
            'kaisa' => 'Kaisa',
            'kha\'zix' => 'Khazix',
            'khazix' => 'Khazix',
            'kog\'maw' => 'KogMaw',
            'kogmaw' => 'KogMaw',
            'leblanc' => 'Leblanc',
            'nunu' => 'Nunu',
            'nunu & willump' => 'Nunu',
            'renata glasc' => 'Renata',
            'vel\'koz' => 'Velkoz',
            'velkoz' => 'Velkoz',
            'dr. mundo' => 'DrMundo',
            'dr mundo' => 'DrMundo',
            'jarvan iv' => 'JarvanIV',
            'master yi' => 'MasterYi',
            'miss fortune' => 'MissFortune',
            'twisted fate' => 'TwistedFate',
            'xin zhao' => 'XinZhao',
            'aurelion sol' => 'AurelionSol',
            'tahm kench' => 'TahmKench',
            'bel\'veth' => 'Belveth',
            'k\'sante' => 'KSante',
        ];

        $lower = strtolower(trim($name));
        if (isset($overrides[$lower])) {
            return $overrides[$lower];
        }

        $clean = preg_replace('/[^a-zA-Z0-9]/', '', ucwords($name));

        return ! empty($clean) ? $clean : 'Ahri';
    }

    /**
     * Obtiene los datos oficiales de un campeón desde el CDN público de Riot.
     *
     * @return array<string, mixed>|null
     */
    public function fetchChampionData(string $championKey): ?array
    {
        try {
            $url = 'https://ddragon.leagueoflegends.com/cdn/'.self::DEFAULT_VERSION.'/data/'.self::LOCALE."/champion/{$championKey}.json";
            $response = Http::timeout(6)->get($url);

            if (! $response->successful()) {
                return null;
            }

            $json = $response->json();

            return $json['data'][$championKey] ?? null;
        } catch (Throwable $e) {
            Log::warning("Error al consultar Riot Data Dragon para {$championKey}: ".$e->getMessage());

            return null;
        }
    }

    /**
     * Sincroniza las skins y las 5 habilidades oficiales de un campeón.
     *
     * @return array{skins: int, abilities: int, success: bool}
     */
    public function syncChampion(Champion $champion): array
    {
        $championKey = $this->normalizeChampionKey($champion->name);
        $data = $this->fetchChampionData($championKey);

        if (! $data) {
            return ['skins' => 0, 'abilities' => 0, 'success' => false];
        }

        $skinsCount = $this->syncSkins($champion, $championKey, $data['skins'] ?? []);
        $abilitiesCount = $this->syncAbilities($champion, $data);

        return [
            'skins' => $skinsCount,
            'abilities' => $abilitiesCount,
            'success' => true,
        ];
    }

    /**
     * Procesa y sincroniza las skins del campeón.
     *
     * @param  array<int, array<string, mixed>>  $skinsData
     */
    public function syncSkins(Champion $champion, string $championKey, array $skinsData): int
    {
        $count = 0;

        foreach ($skinsData as $skinItem) {
            $num = (int) ($skinItem['num'] ?? 0);
            if ($num === 0) {
                continue; // La skin 0 es el aspecto clásico base
            }

            $skinName = (string) ($skinItem['name'] ?? '');
            if (empty($skinName) || strtolower($skinName) === 'default') {
                continue;
            }

            $splashUrl = "https://ddragon.leagueoflegends.com/cdn/img/champion/splash/{$championKey}_{$num}.jpg";
            $tier = $this->guessSkinTier($skinName);
            $rp = $this->guessSkinPrice($tier);

            Skin::updateOrCreate(
                [
                    'champion_id' => $champion->id,
                    'name' => $skinName,
                ],
                [
                    'splash_art_url' => $splashUrl,
                    'tier' => $tier,
                    'price_rp' => $rp,
                    'is_default' => false,
                ]
            );

            $count++;
        }

        return $count;
    }

    /**
     * Procesa y sincroniza las 5 habilidades oficiales del campeón (Pasiva, Q, W, E, R).
     *
     * @param  array<string, mixed>  $data
     */
    public function syncAbilities(Champion $champion, array $data): int
    {
        $count = 0;

        // 1. Pasiva
        if (! empty($data['passive'])) {
            $passive = $data['passive'];
            $icon = ! empty($passive['image']['full'])
                ? 'https://ddragon.leagueoflegends.com/cdn/'.self::DEFAULT_VERSION.'/img/passive/'.$passive['image']['full']
                : null;

            Ability::updateOrCreate(
                [
                    'champion_id' => $champion->id,
                    'slot' => 'P',
                ],
                [
                    'name' => $passive['name'] ?? 'Pasiva',
                    'description' => strip_tags((string) ($passive['description'] ?? 'Efecto pasivo del campeón.')),
                    'icon_url' => $icon,
                    'cooldown' => null,
                    'cost' => 'Sin coste',
                ]
            );
            $count++;
        }

        // 2. Habilidades de combate Q, W, E, R
        $slots = ['Q', 'W', 'E', 'R'];
        $spells = $data['spells'] ?? [];

        foreach ($slots as $index => $slot) {
            if (isset($spells[$index])) {
                $spell = $spells[$index];
                $icon = ! empty($spell['image']['full'])
                    ? 'https://ddragon.leagueoflegends.com/cdn/'.self::DEFAULT_VERSION.'/img/spell/'.$spell['image']['full']
                    : null;

                $cooldown = ! empty($spell['cooldownBurn']) ? $spell['cooldownBurn'].' s' : null;
                $cost = ! empty($spell['costBurn']) && $spell['costBurn'] !== '0' ? $spell['costBurn'].' de maná' : 'Sin coste';

                Ability::updateOrCreate(
                    [
                        'champion_id' => $champion->id,
                        'slot' => $slot,
                    ],
                    [
                        'name' => $spell['name'] ?? "Habilidad {$slot}",
                        'description' => strip_tags((string) ($spell['description'] ?? 'Efecto de combate.')),
                        'icon_url' => $icon,
                        'cooldown' => $cooldown,
                        'cost' => $cost,
                    ]
                );
                $count++;
            }
        }

        return $count;
    }

    /**
     * Deduce la rareza o tier de la skin en base a su nombre oficial.
     */
    private function guessSkinTier(string $skinName): string
    {
        $lower = strtolower($skinName);

        if (str_contains($lower, 'prestigio') || str_contains($lower, 'prestigiosa') || str_contains($lower, 'mítica') || str_contains($lower, 'arcane') || str_contains($lower, 'hextech')) {
            return 'Mítica';
        }

        if (str_contains($lower, 'definitiva') || str_contains($lower, 'dj') || str_contains($lower, 'elementalista')) {
            return 'Definitiva';
        }

        if (str_contains($lower, 'guardiana de las estrellas') || str_contains($lower, 'guardiana estelar') || str_contains($lower, 'proyecto') || str_contains($lower, 'flor espiritual') || str_contains($lower, 'pulso de fuego') || str_contains($lower, 'portador del anochecer')) {
            return 'Legendaria';
        }

        return 'Épica';
    }

    /**
     * Asigna un costo estándar en RP según el tier.
     */
    private function guessSkinPrice(string $tier): int
    {
        return match ($tier) {
            'Definitiva' => 3250,
            'Legendaria' => 1820,
            'Mítica' => 975,
            default => 1350,
        };
    }
}
