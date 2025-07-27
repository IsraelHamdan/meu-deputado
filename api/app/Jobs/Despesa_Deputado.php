<?php

namespace App\Jobs;

use App\Models\Deputado;
use App\Models\Despesa;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Despesa_Deputado implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Deputado $deputado)
    {
        Log::info('Despesa_Deputado iniciou', [
            'deputado_id' => $this->deputado->id,
            'deputado_nome' => $this->deputado->nome
        ]);
    }

    public function handle()
    {
        $baseUrl = rtrim(env('CAMARA_API'), '/');
        $url = "{$baseUrl}/deputados/{$this->deputado->camara_id}/despesas";

        Log::info('Buscando despesas do deputado', [
            'deputado_id' => $this->deputado->id,
            'camara_id' => $this->deputado->camara_id,
            'base_url' => $url
        ]);

        try {
            $pagina = 1;
            $totalDespesasProcessadas = 0;

            do {
                $params = [
                    'pagina' => $pagina,
                    'itens' => 20,
                    'ordem' => 'ASC',
                    'ordenarPor' => 'ano'
                ];

                $fullUrl = $url . '?' . http_build_query($params);

                Log::info('Requisição para despesas', [
                    'url' => $fullUrl,
                    'deputado_id' => $this->deputado->id,
                    'pagina' => $pagina
                ]);

                $response = Http::timeout(30)->get($fullUrl);

                if ($response->failed()) {
                    Log::error('Falha ao buscar despesas', [
                        'deputado_id' => $this->deputado->id,
                        'status' => $response->status(),
                        'url' => $fullUrl,
                        'response_body' => substr($response->body(), 0, 500)
                    ]);
                    break;
                }

                $dados = $response->json('dados', []);
                $links = $response->json('links', []);

                Log::info('Resposta de despesas recebida', [
                    'deputado_id' => $this->deputado->id,
                    'pagina' => $pagina,
                    'total_despesas_pagina' => count($dados),
                    'status' => $response->status()
                ]);

                // Se não há dados, acabou
                if (empty($dados)) {
                    Log::info('Nenhuma despesa encontrada - fim da paginação', [
                        'deputado_id' => $this->deputado->id,
                        'pagina' => $pagina
                    ]);
                    break;
                }

                $despesasProcessadasPagina = 0;
                foreach ($dados as $item) {
                    try {
                        Despesa::updateOrCreate([
                            'deputado_id' => $this->deputado->id,
                            'cod_documento' => $item['codDocumento'] ?? null,
                            'num_documento' => $item['numDocumento'] ?? null,
                            'data_documento' => $item['dataDocumento'] ?? null,
                        ], [
                            'ano' => $item['ano'] ?? null,
                            'cnpj_cpf_fornecedor' => $item['cnpjCpfFornecedor'] ?? null,
                            'cod_lote' => $item['codLote'] ?? null,
                            'cod_tipo_documento' => $item['codTipoDocumento'] ?? null,
                            'mes' => $item['mes'] ?? null,
                            'nome_fornecedor' => $item['nomeFornecedor'] ?? null,
                            'num_ressarcimento' => $item['numRessarcimento'] ?? null,
                            'parcela' => $item['parcela'] ?? null,
                            'tipo_despesa' => $item['tipoDespesa'] ?? null,
                            'tipo_documento' => $item['tipoDocumento'] ?? null,
                            'url_documento' => $item['urlDocumento'] ?? null,
                            'valor_documento' => $item['valorDocumento'] ?? null,
                            'valor_glosa' => $item['valorGlosa'] ?? null,
                            'valor_liquido' => $item['valorLiquido'] ?? null,
                        ]);
                        $despesasProcessadasPagina++;
                        $totalDespesasProcessadas++;
                    } catch (\Exception $e) {
                        Log::error('Erro ao salvar despesa individual', [
                            'deputado_id' => $this->deputado->id,
                            'cod_documento' => $item['codDocumento'] ?? 'N/A',
                            'error' => $e->getMessage()
                        ]);
                    }
                }

                Log::info('Despesas processadas na página', [
                    'deputado_id' => $this->deputado->id,
                    'pagina' => $pagina,
                    'processadas' => $despesasProcessadasPagina,
                    'total_na_pagina' => count($dados)
                ]);

                // Verificar se há próxima página usando links ou verificando quantidade
                $nextLink = collect($links)->firstWhere('rel', 'next');

                // Se não há link next ou retornou menos que 20 itens, acabou
                if (!$nextLink || count($dados) < 20) {
                    Log::info('Fim da paginação de despesas', [
                        'deputado_id' => $this->deputado->id,
                        'ultima_pagina' => $pagina,
                        'tem_next_link' => (bool)$nextLink,
                        'itens_ultima_pagina' => count($dados)
                    ]);
                    break;
                }

                $pagina++;

            } while (true);

            Log::info('Sincronização de despesas concluída', [
                'deputado_id' => $this->deputado->id,
                'total_despesas_processadas' => $totalDespesasProcessadas,
                'total_paginas' => $pagina
            ]);

        } catch (\Exception $e) {
            // Correção do Log::error com array como contexto
            Log::error("Erro ao salvar despesas do deputado", [
                'deputado_id' => $this->deputado->id,
                'camara_id' => $this->deputado->camara_id,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Re-lançar a exceção para marcar a job como falhada
            throw $e;
        }
    }

    /**
     * Determinar quantas tentativas a job deve ter
     */
    public $tries = 3;

    /**
     * Determinar o timeout da job
     */
    public $timeout = 180; // 3 minutos para despesas
}
