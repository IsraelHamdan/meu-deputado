<?php

namespace App\Jobs;

use App\Models\Deputado;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncDeputados implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $pagina;
    public int $itensPorPagina;
    public static int $totalGlobalProcessados = 0;

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

    public function handle(): void
    {
        $baseUrl = rtrim(env('CAMARA_API'), '/');
        $url = "{$baseUrl}/deputados";

        // Vamos testar primeiro SEM parâmetros de paginação para ver o que retorna
        if ($this->pagina === 1) {
            $this->testarApiSemPaginacao($url);
        }

        $params = [
            'pagina' => $this->pagina,
            'itens' => $this->itensPorPagina,
            'ordem' => 'ASC',
            'ordenarPor' => 'nome'
        ];

        $fullUrl = $url . '?' . http_build_query($params);

        Log::info('=== REQUISIÇÃO DEBUG ===', [
            'url_completa' => $fullUrl,
            'pagina' => $this->pagina,
            'parametros' => $params
        ]);

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

            // Log completo da resposta da API
            $responseData = $response->json();

            Log::info('=== RESPOSTA COMPLETA DA API ===', [
                'status' => $response->status(),
                'pagina' => $this->pagina,
                'keys_na_resposta' => array_keys($responseData),
                'tem_dados' => isset($responseData['dados']),
                'tem_links' => isset($responseData['links']),
                'quantidade_dados' => isset($responseData['dados']) ? count($responseData['dados']) : 0,
                'links' => $responseData['links'] ?? 'Nenhum link encontrado'
            ]);

            $data = $response->json('dados', []);

            if (empty($data)) {
                Log::warning('=== PÁGINA VAZIA ENCONTRADA ===', [
                    'pagina' => $this->pagina,
                    'url' => $fullUrl,
                    'resposta_completa' => $responseData
                ]);
                return;
            }

            $deputadosProcessados = 0;
            foreach ($data as $index => $item) {
                try {
                    Log::info('=== PROCESSANDO DEPUTADO ===', [
                        'pagina' => $this->pagina,
                        'index_na_pagina' => $index,
                        'deputado_id' => $item['id'] ?? 'N/A',
                        'deputado_nome' => $item['nome'] ?? 'N/A'
                    ]);

                    $deputado = Deputado::updateOrCreate(
                        ['camara_id' => $item['id']],
                        [
                            'nome' => $item['nome'] ?? null,
                            'email' => $item['email'] ?? null,
                            'id_legislatura' => $item['idLegislatura'] ?? null,
                            'sigla_partido' => $item['siglaPartido'] ?? null,
                            'sigla_uf' => $item['siglaUf'] ?? null,
                            'uri' => $item['uri'] ?? null,
                            'uri_partido' => $item['uriPartido'] ?? null,
                            'url_foto' => $item['urlFoto'] ?? null
                        ]
                    );

                    $deputadosProcessados++;
                    self::$totalGlobalProcessados++;

                } catch (\Exception $e) {
                    Log::error('=== ERRO AO SALVAR DEPUTADO ===', [
                        'deputado_id' => $item['id'] ?? 'unknown',
                        'error' => $e->getMessage(),
                        'deputado_data' => $item
                    ]);
                }
            }

            Log::info('=== PÁGINA PROCESSADA ===', [
                'pagina' => $this->pagina,
                'deputados_na_pagina' => count($data),
                'deputados_processados' => $deputadosProcessados,
                'total_global_processados' => self::$totalGlobalProcessados,
                'itens_esperados' => $this->itensPorPagina,
                'deve_continuar' => count($data) >= $this->itensPorPagina
            ]);

            // Verificar se deve continuar
            if (count($data) < $this->itensPorPagina) {
                Log::info('=== SINCRONIZAÇÃO CONCLUÍDA ===', [
                    'ultima_pagina' => $this->pagina,
                    'total_deputados_processados' => self::$totalGlobalProcessados,
                    'itens_na_ultima_pagina' => count($data)
                ]);
                return;
            }

            // Testar também os links da API
            $links = $response->json('links', []);
            if (!empty($links)) {
                Log::info('=== LINKS ENCONTRADOS ===', [
                    'pagina' => $this->pagina,
                    'links' => $links,
                    'tem_next' => collect($links)->firstWhere('rel', 'next') ? 'SIM' : 'NÃO'
                ]);
            }

            // Continuar para próxima página
            $proximaPagina = $this->pagina + 1;
            Log::info('=== DESPACHANDO PRÓXIMA PÁGINA ===', [
                'pagina_atual' => $this->pagina,
                'proxima_pagina' => $proximaPagina
            ]);

            SyncDeputados::dispatch($proximaPagina, $this->itensPorPagina);

        } catch (\Exception $e) {
            Log::error('=== ERRO GERAL NA JOB ===', [
                'pagina' => $this->pagina,
                'url' => $fullUrl ?? 'N/A',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Testa a API sem parâmetros de paginação para ver o comportamento padrão
     */
    private function testarApiSemPaginacao(string $baseUrl): void
    {
        try {
            Log::info('=== TESTANDO API SEM PAGINAÇÃO ===');

            $response = Http::timeout(30)->get($baseUrl);
            $data = $response->json();

            Log::info('=== RESULTADO TESTE SEM PAGINAÇÃO ===', [
                'status' => $response->status(),
                'tem_dados' => isset($data['dados']),
                'quantidade_sem_paginacao' => isset($data['dados']) ? count($data['dados']) : 0,
                'links' => $data['links'] ?? 'Nenhum',
                'primeiros_3_deputados' => isset($data['dados']) ?
                    array_slice($data['dados'], 0, 3) : 'Nenhum dado'
            ]);

        } catch (\Exception $e) {
            Log::error('=== ERRO NO TESTE SEM PAGINAÇÃO ===', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public $tries = 1; // Reduzir tentativas para debug
    public $timeout = 120;
}
