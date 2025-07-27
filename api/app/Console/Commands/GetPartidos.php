<?php

namespace App\Console\Commands;

use App\Jobs\SyncPartidos;
use Illuminate\Console\Command;

class GetPartidos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:partidos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Busca os partidos da API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Buscando partidos');
        $pagina = 1;
        $itensPorPagina = 100;

        dispatch(new SyncPartidos($pagina, $itensPorPagina));

        $this->info('Partidos buscados');;

        return Command::SUCCESS;
    }
}
