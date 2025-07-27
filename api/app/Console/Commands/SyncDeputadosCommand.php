<?php

namespace App\Console\Commands;

use App\Jobs\SyncDeputados;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SyncDeputadosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:deputados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispara a job para sincronizar deputados da API';

    /**
     * Execute the console command.
     */


    public function handle()
    {
        SyncDeputados::dispatch(1); // Começa pela página 1
        $this->info('Job disparada: busca dos deputados iniciadas');
    }

}
