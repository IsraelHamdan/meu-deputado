<?php

namespace App\Jobs;

use App\Models\Partido;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class SyncPartidos implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, \Illuminate\Bus\Queueable, SerializesModels;

    public int $pagina;
    public int $itensPorPagina;
    public static int $totalGlobalProcessados = 0;

    /**
     * Create a new job instance.
     */
    public function __construct(int $pagina = 1, int $itensPorPagina = 20)
    {
        $this->pagina = $pagina;
        $this->itensPorPagina = $itensPorPagina;

        Log::info('=== SyncDeputados DEBUG ===', [
            'pagina' => $this->pagina,
            'itens_por_pagina' => $this->itensPorPagina,
            'total_global_ate_agora' => self::$totalGlobalProcessados
        ]);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $baseUrl = rtrim(env('CAMARA_API'), '/');
        $url = "{$baseUrl}/partidos";
        $params = [
            'pagina' => $this->pagina,
            'itens' => $this->itensPorPagina,
            'ordem' => 'ASC',
            'ordenarPor' => 'nome'
        ];
        $fullUrl = $url . '?' . http_build_query($params);

        try {
            $response = Http::timeout(30)->get($fullUrl);
            if ($response->failed()) {
                Log::error('=== FALHA NA REQUISIÇÃO ===', [
                    'status' => $response->status(),
                    'url' => $fullUrl,
                    'response_body' => substr($response->body(), 0, 1000)
                ]);
                return;
            }

            $responseData = $response->json('dados', []);
            if (empty($responseData)) {
                Log::warning('=== PÁGINA VAZIA ENCONTRADA ===', [
                    'pagina' => $this->pagina,
                    'url' => $fullUrl,
                    'resposta_completa' => $responseData
                ]);
                return;
            }
            foreach ($responseData as $index => $item) {
                try {
                    $partido = Partido::updateOrCreate(
                        ['id' => $item['id']],
                        [
                            'nome' => $item['nome'],
                            'sigla' => $item['sigla'],
                            'uri' => $item['uri']
                        ]
                    );
                } catch (\Exception $e) {
                    Log::error('Erro ao salvar partido', [
                        'partido' => $item,
                        'exception' => $e->getMessage()
                    ]);
                }
            }
            Log::info('=== PARTIDOS IMPORTADOS COM SUCESSO ===', [
                'pagina' => $this->pagina,
                'total_partidos' => count($data)
            ]);
        } catch (\Exception $e) {
            Log::error('Erro inesperado ao executar Job de partidos', [
                'exception' => $e->getMessage(),
                'url' => $fullUrl
            ]);
        }
    }
}
