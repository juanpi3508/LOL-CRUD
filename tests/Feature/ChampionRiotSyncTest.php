<?php

namespace Tests\Feature;

use App\Models\Champion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChampionRiotSyncTest extends TestCase
{
    use RefreshDatabase;

    private function getMockRiotChampionData(): array
    {
        return [
            'data' => [
                'Ahri' => [
                    'id' => 'Ahri',
                    'name' => 'Ahri',
                    'title' => 'La Zorra de Nueve Colas',
                    'skins' => [
                        [
                            'id' => '103000',
                            'num' => 0,
                            'name' => 'default',
                        ],
                        [
                            'id' => '103001',
                            'num' => 1,
                            'name' => 'Ahri dinastía',
                        ],
                        [
                            'id' => '103027',
                            'num' => 27,
                            'name' => 'Ahri flor espiritual',
                        ],
                    ],
                    'passive' => [
                        'name' => 'Ladrona de esencias',
                        'description' => 'Ahri acumula esencias al golpear enemigos.',
                        'image' => [
                            'full' => 'Ahri_Passive.png',
                        ],
                    ],
                    'spells' => [
                        [
                            'id' => 'AhriQ',
                            'name' => 'Orbe del engaño',
                            'description' => 'Ahri lanza y recupera su orbe infligiendo daño.',
                            'cooldownBurn' => '7',
                            'costBurn' => '60/70/80',
                            'image' => [
                                'full' => 'AhriQ.png',
                            ],
                        ],
                        [
                            'id' => 'AhriW',
                            'name' => 'Fuego zorruno',
                            'description' => 'Ahri libera fuegos zorrunos que buscan enemigos.',
                            'cooldownBurn' => '9/8/7',
                            'costBurn' => '30',
                            'image' => [
                                'full' => 'AhriW.png',
                            ],
                        ],
                        [
                            'id' => 'AhriE',
                            'name' => 'Hechizo',
                            'description' => 'Ahri lanza un beso que enamora al enemigo impactado.',
                            'cooldownBurn' => '12',
                            'costBurn' => '60',
                            'image' => [
                                'full' => 'AhriE.png',
                            ],
                        ],
                        [
                            'id' => 'AhriR',
                            'name' => 'Impulso espiritual',
                            'description' => 'Ahri se desplaza velozmente infligiendo daño a enemigos cercanos.',
                            'cooldownBurn' => '130/105/80',
                            'costBurn' => '100',
                            'image' => [
                                'full' => 'AhriR.png',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    public function test_show_automatically_syncs_skins_and_abilities_when_empty(): void
    {
        Http::fake([
            'https://ddragon.leagueoflegends.com/*' => Http::response($this->getMockRiotChampionData(), 200),
        ]);

        $champion = Champion::create([
            'name' => 'Ahri',
            'title' => 'La Zorra de Nueve Colas',
            'role' => 'Mage',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'Una vastaaya conectada con la magia de Jonia.',
        ]);

        $this->assertEquals(0, $champion->skins()->count());
        $this->assertEquals(0, $champion->abilities()->count());

        $response = $this->get(route('champions.show', $champion));

        $response->assertStatus(200);

        // Se deben haber creado las 2 skins no-default y las 5 habilidades
        $this->assertDatabaseHas('skins', [
            'champion_id' => $champion->id,
            'name' => 'Ahri flor espiritual',
            'tier' => 'Legendaria',
        ]);

        $this->assertDatabaseHas('abilities', [
            'champion_id' => $champion->id,
            'slot' => 'P',
            'name' => 'Ladrona de esencias',
        ]);

        $this->assertDatabaseHas('abilities', [
            'champion_id' => $champion->id,
            'slot' => 'R',
            'name' => 'Impulso espiritual',
        ]);

        $response->assertSee('Ahri flor espiritual');
        $response->assertSee('Ladrona de esencias');
        $response->assertSee('Impulso espiritual');
    }

    public function test_manual_sync_endpoint_updates_champion_data(): void
    {
        Http::fake([
            'https://ddragon.leagueoflegends.com/*' => Http::response($this->getMockRiotChampionData(), 200),
        ]);

        $champion = Champion::create([
            'name' => 'Ahri',
            'title' => 'La Zorra de Nueve Colas',
            'role' => 'Mage',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'Una vastaaya conectada con la magia de Jonia.',
        ]);

        $response = $this->post(route('champions.sync', $champion));

        $response->assertRedirect(route('champions.show', $champion));
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('skins', 2);
        $this->assertDatabaseCount('abilities', 5);
    }

    public function test_artisan_lol_sync_command_syncs_champions(): void
    {
        Http::fake([
            'https://ddragon.leagueoflegends.com/*' => Http::response($this->getMockRiotChampionData(), 200),
        ]);

        $champion = Champion::create([
            'name' => 'Ahri',
            'title' => 'La Zorra de Nueve Colas',
            'role' => 'Mage',
            'resource_type' => 'Maná',
            'difficulty' => 'Media',
            'lore' => 'Una vastaaya conectada con la magia de Jonia.',
        ]);

        $this->artisan('lol:sync', ['champion' => 'Ahri'])
            ->assertSuccessful();

        $this->assertDatabaseHas('abilities', [
            'champion_id' => $champion->id,
            'slot' => 'Q',
            'name' => 'Orbe del engaño',
        ]);
    }

    public function test_graceful_handling_when_riot_api_fails(): void
    {
        Http::fake([
            'https://ddragon.leagueoflegends.com/*' => Http::response([], 500),
        ]);

        $champion = Champion::create([
            'name' => 'CampeonInexistente',
            'title' => 'El Desconocido',
            'role' => 'Assassin',
            'resource_type' => 'Sin maná',
            'difficulty' => 'Alta',
            'lore' => 'Un campeón ficticio para pruebas de error.',
        ]);

        $response = $this->post(route('champions.sync', $champion));

        $response->assertRedirect(route('champions.show', $champion));
        $response->assertSessionHas('error');
    }
}
