<?php

namespace App\Console\Commands;

use App\Jobs\SyncDeputados;
use App\Jobs\SyncPartidos;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;


class StartLaravel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inicia a aplicação completa: queue worker, jobs e servidor';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando aplicação');

        // Processos que vão rodar em paralelo
        $processes = [];

        // 1. Iniciar queue worker
        $this->info('Iniciando o queue worker...');
        $processes['queue'] = Process::start(['php', 'artisan', 'queue:work']);

        // 2. Aguardar worker inicializar
        sleep(2);

        // 3. Despachar jobs
        $this->info('Despachando jobs...');
        SyncPartidos::dispatch(1, 20);
        SyncDeputados::dispatch(1);

        // 4. Iniciar servidor
        $this->info('Iniciando o servidor...');
        $processes['server'] = Process::start(['php', 'artisan', 'serve']);

        $this->info('✅ Aplicação rodando! Pressione Ctrl+C para parar');

        // 5. Aguardar todos os processos (mantém rodando)
        foreach ($processes as $name => $process) {
            $process->wait();
        }

        return 0;
    }
}
