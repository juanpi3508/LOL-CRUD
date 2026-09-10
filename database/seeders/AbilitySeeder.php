<?php

namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Champion;
use Illuminate\Database\Seeder;

class AbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $abilitiesData = [
            'Jinx' => [
                [
                    'slot' => 'P',
                    'name' => '¡A divertirse!',
                    'description' => 'Jinx recibe una gran bonificación de velocidad de movimiento y velocidad de ataque adicional cada vez que ayuda a destruir una estructura enemiga o a asesinar a un campeón enemigo o a un monstruo épico de la jungla.',
                    'icon_url' => 'https://ddragon.leagueoflegends.com/cdn/14.5.1/img/passive/Jinx_Passive.png',
                    'cooldown' => null,
                    'cost' => 'Sin coste',
                ],
                [
                    'slot' => 'Q',
                    'name' => '¡Cambio de armas!',
                    'description' => 'Jinx modifica sus ataques básicos alternando entre Pium-Pium, su ametralladora que otorga velocidad de ataque acumulable, y Espinas, su lanzacohetes que inflige daño de área a mayor alcance a cambio de maná.',
                    'icon_url' => 'https://ddragon.leagueoflegends.com/cdn/14.5.1/img/spell/JinxQ.png',
                    'cooldown' => '1 s',
                    'cost' => '20 de maná por tiro',
                ],
                [
                    'slot' => 'W',
                    'name' => '¡Chispas!',
                    'description' => 'Jinx utiliza a Chispitas, su pistola de rayos, para disparar un proyectil electrizante que inflige daño físico masivo al primer enemigo alcanzado, revelándolo y ralentizándolo fuertemente durante 2 segundos.',
                    'icon_url' => 'https://ddragon.leagueoflegends.com/cdn/14.5.1/img/spell/JinxW.png',
                    'cooldown' => '8 / 7 / 6 / 5 / 4 s',
                    'cost' => '50 / 60 / 70 / 80 / 90 de maná',
                ],
                [
                    'slot' => 'E',
                    'name' => '¡Mascafuegos masticadores!',
                    'description' => 'Jinx lanza una hilera de tres granadas trampa que se arman tras un breve instante. Los campeones enemigos que las pisen quedan inmovilizados durante 1.5 segundos y reciben daño mágico.',
                    'icon_url' => 'https://ddragon.leagueoflegends.com/cdn/14.5.1/img/spell/JinxE.png',
                    'cooldown' => '24 / 20.5 / 17 / 13.5 / 10 s',
                    'cost' => '90 de maná',
                ],
                [
                    'slot' => 'R',
                    'name' => '¡Supermegacohete mortal!',
                    'description' => 'Jinx dispara un enorme cohete que recorre todo el mapa en línea recta. El daño aumenta según la distancia recorrida e inflige daño físico masivo en área en función de la vida que le falte a los enemigos impactados.',
                    'icon_url' => 'https://ddragon.leagueoflegends.com/cdn/14.5.1/img/spell/JinxR.png',
                    'cooldown' => '85 / 65 / 45 s',
                    'cost' => '100 de maná',
                ],
            ],
            'Caitlyn' => [
                [
                    'slot' => 'P',
                    'name' => 'Disparo a la cabeza',
                    'description' => 'Cada pocos disparos básicos o al acertar sobre un objetivo atrapado en una trampa o red, Caitlyn realiza un Disparo a la cabeza con alcance duplicado que inflige daño físico crítico masivo.',
                    'icon_url' => 'https://ddragon.leagueoflegends.com/cdn/14.5.1/img/passive/Caitlyn_Headshot.png',
                    'cooldown' => null,
                    'cost' => 'Sin coste',
                ],
                [
                    'slot' => 'Q',
                    'name' => 'Pacificadora de Piltóver',
                    'description' => 'Caitlyn carga su rifle durante 1 segundo para disparar un proyectil perforante de alta velocidad que inflige gran cantidad de daño físico a todos los enemigos que atraviesa.',
                    'icon_url' => 'https://ddragon.leagueoflegends.com/cdn/14.5.1/img/spell/CaitlynPiltoverPeacemaker.png',
                    'cooldown' => '10 / 9 / 8 / 7 / 6 s',
                    'cost' => '50 / 60 / 70 / 80 / 90 de maná',
                ],
                [
                    'slot' => 'W',
                    'name' => 'Trampa para yordles',
                    'description' => 'Caitlyn coloca una trampa oculta en el suelo. Cuando un campeón enemigo la pisa, queda inmovilizado y revelado durante 1.5 segundos, activando inmediatamente un Disparo a la cabeza de alcance máximo.',
                    'icon_url' => 'https://ddragon.leagueoflegends.com/cdn/14.5.1/img/spell/CaitlynYordleTrap.png',
                    'cooldown' => '0.5 s (recarga 30/24/18/12s)',
                    'cost' => '20 de maná',
                ],
                [
                    'slot' => 'E',
                    'name' => 'Red del calibre 90',
                    'description' => 'Caitlyn dispara una pesada red que ralentiza un 50% al enemigo impactado y la empuja a ella hacia atrás por el retroceso, activando un Disparo a la cabeza potenciado.',
                    'icon_url' => 'https://ddragon.leagueoflegends.com/cdn/14.5.1/img/spell/CaitlynEntrapment.png',
                    'cooldown' => '16 / 14.5 / 13 / 11.5 / 10 s',
                    'cost' => '75 de maná',
                ],
                [
                    'slot' => 'R',
                    'name' => 'As en la manga',
                    'description' => 'Caitlyn se toma un momento para apuntar cuidadosamente a un campeón enemigo a enorme distancia y dispara un tiro perfecto infalible que inflige daño colosal. Otros campeones enemigos pueden interceptar la bala.',
                    'icon_url' => 'https://ddragon.leagueoflegends.com/cdn/14.5.1/img/spell/CaitlynAceintheHole.png',
                    'cooldown' => '90 / 75 / 60 s',
                    'cost' => '100 de maná',
                ],
            ],
        ];

        foreach ($abilitiesData as $championName => $abilities) {
            $champion = Champion::where('name', $championName)->first();

            if ($champion) {
                foreach ($abilities as $abilityData) {
                    Ability::updateOrCreate(
                        [
                            'champion_id' => $champion->id,
                            'slot' => $abilityData['slot'],
                        ],
                        [
                            'name' => $abilityData['name'],
                            'description' => $abilityData['description'],
                            'icon_url' => $abilityData['icon_url'],
                            'cooldown' => $abilityData['cooldown'],
                            'cost' => $abilityData['cost'],
                        ]
                    );
                }
            }
        }
    }
}
