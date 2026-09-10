<?php

namespace App\Console\Commands;

use App\Models\Champion;
use App\Services\RiotDataDragonService;
use Illuminate\Console\Command;

class SyncChampionDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lol:sync {champion? : Nombre del campeón o ID a sincronizar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza skins y habilidades oficiales de los campeones desde Riot Data Dragon';

    /**
     * Execute the console command.
     */
    public function handle(RiotDataDragonService $riotService): int
    {
        $championInput = $this->argument('champion');

        $query = Champion::query();

        if ($championInput) {
            $query->where('id', $championInput)
                ->orWhere('name', 'like', "%{$championInput}%");
        }

        $champions = $query->get();

        if ($champions->isEmpty()) {
            $this->warn('No se encontraron campeones para sincronizar.');

            return self::FAILURE;
        }

        $this->info("Iniciando sincronización con Riot Data Dragon para {$champions->count()} campeón(es)...");

        $bar = $this->output->createProgressBar($champions->count());
        $bar->start();

        $totalSkins = 0;
        $totalAbilities = 0;

        foreach ($champions as $champion) {
            $result = $riotService->syncChampion($champion);

            if ($result['success']) {
                $totalSkins += $result['skins'];
                $totalAbilities += $result['abilities'];
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('¡Sincronización completada exitosamente!');
        $this->table(
            ['Métrica', 'Total'],
            [
                ['Campeones procesados', $champions->count()],
                ['Skins vinculadas', $totalSkins],
                ['Habilidades registradas', $totalAbilities],
            ]
        );

        return self::SUCCESS;
    }
}
