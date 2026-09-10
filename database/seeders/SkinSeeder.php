<?php

namespace Database\Seeders;

use App\Models\Champion;
use App\Models\Skin;
use Illuminate\Database\Seeder;

class SkinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skinsData = [
            'Jinx' => [
                [
                    'name' => 'Jinx Mafiosa',
                    'splash_art_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Jinx_1.jpg',
                    'tier' => 'Épica',
                    'price_rp' => 975,
                ],
                [
                    'name' => 'Jinx Artificiera',
                    'splash_art_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Jinx_2.jpg',
                    'tier' => 'Épica',
                    'price_rp' => 1350,
                ],
                [
                    'name' => 'Jinx Guardiana Estelar',
                    'splash_art_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Jinx_4.jpg',
                    'tier' => 'Legendaria',
                    'price_rp' => 1820,
                ],
                [
                    'name' => 'Jinx Proyecto',
                    'splash_art_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Jinx_20.jpg',
                    'tier' => 'Épica',
                    'price_rp' => 1350,
                ],
                [
                    'name' => 'Jinx Arcane',
                    'splash_art_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Jinx_37.jpg',
                    'tier' => 'Mítica',
                    'price_rp' => 975,
                ],
            ],
            'Caitlyn' => [
                [
                    'name' => 'Caitlyn de la Resistencia',
                    'splash_art_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Caitlyn_1.jpg',
                    'tier' => 'Clásica',
                    'price_rp' => 520,
                ],
                [
                    'name' => 'Caitlyn Cazadora de Cabezas',
                    'splash_art_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Caitlyn_4.jpg',
                    'tier' => 'Épica',
                    'price_rp' => 975,
                ],
                [
                    'name' => 'Caitlyn Pulso de Fuego',
                    'splash_art_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Caitlyn_10.jpg',
                    'tier' => 'Legendaria',
                    'price_rp' => 1820,
                ],
                [
                    'name' => 'Caitlyn Arcane',
                    'splash_art_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Caitlyn_19.jpg',
                    'tier' => 'Mítica',
                    'price_rp' => 975,
                ],
            ],
            'Ahri' => [
                [
                    'name' => 'Ahri Flor Espiritual',
                    'splash_art_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Ahri_27.jpg',
                    'tier' => 'Legendaria',
                    'price_rp' => 1820,
                ],
                [
                    'name' => 'Ahri K/DA All Out',
                    'splash_art_url' => 'https://ddragon.leagueoflegends.com/cdn/img/champion/splash/Ahri_28.jpg',
                    'tier' => 'Épica',
                    'price_rp' => 1350,
                ],
            ],
        ];

        foreach ($skinsData as $championName => $skins) {
            $champion = Champion::where('name', $championName)->first();

            if ($champion) {
                foreach ($skins as $skinData) {
                    Skin::updateOrCreate(
                        [
                            'champion_id' => $champion->id,
                            'name' => $skinData['name'],
                        ],
                        [
                            'splash_art_url' => $skinData['splash_art_url'],
                            'tier' => $skinData['tier'],
                            'price_rp' => $skinData['price_rp'],
                            'is_default' => false,
                        ]
                    );
                }
            }
        }
    }
}
