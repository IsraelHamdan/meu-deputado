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

    }

    public function handle()
    {
        $url = env('CAMARA_API_URL') . "/deputados/{$this->deputado->camara_id}/despesas";
        try {
            $resposta = Http::get($url);
            foreach ($resposta['dados'] as $item) {
                Despesa::updateOrCreate(
                    [
                        // critério de unicidade para evitar duplicatas
                        'deputado_id' => $this->deputado->id,
                        'cod_documento' => $item['codDocumento'],
                        'num_documento' => $item['numDocumento'],
                        'data_documento' => $item['dataDocumento'],
                    ],
                    [
                        'ano' => $item['ano'],
                        'cnpj_cpf_fornecedor' => $item['cnpjCpfFornecedor'],
                        'cod_lote' => $item['codLote'],
                        'cod_tipo_documento' => $item['codTipoDocumento'],
                        'mes' => $item['mes'],
                        'nome_fornecedor' => $item['nomeFornecedor'],
                        'num_ressarcimento' => $item['numRessarcimento'],
                        'parcela' => $item['parcela'],
                        'tipo_despesa' => $item['tipoDespesa'],
                        'tipo_documento' => $item['tipoDocumento'],
                        'url_documento' => $item['urlDocumento'],
                        'valor_documento' => $item['valorDocumento'],
                        'valor_glosa' => $item['valorGlosa'],
                        'valor_liquido' => $item['valorLiquido'],
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error("Erro ao salvar despesas do deputado {$this->deputado->id}: " . $e->getMessage(), $e->getCode());
        }

    }
}
