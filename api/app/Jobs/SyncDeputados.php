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
    public int $page;
    public function __construct(int $page = 1)
    {
        $this->page = $page;
    }

    public function handle(): void
    {
        $url = env('CAMARA_API_URL') . "/deputados?page={$this->page}";

        try {
            $response = Http::get($url);
            if($response->failed()) return;

            $data = $response->json('dados', []);

            foreach ($data as $item) {
                $deputado = Deputado::updateOrCreate(
                    ['camara_id' => $item['id']],
                    [
                        'nome' => $item['nome'],
                        'email' => $item['email'],
                        'id_legislatura' => $item['idLegislatura'],
                        'sigla_partido' => $item['siglaPartido'],
                        'sigla_uf' => $item['siglaUf'],
                        'uri' => $item['uri'],
                        'uri_partido' => $item['uriPartido'],
                        'url_foto' => $item['urlFoto']

                    ]
                );
                Despesa_Deputado::dispatch($deputado);
            }
        } catch (\Exception $e) {
            Log::error("Erro ao buscar deputados: {$e->getMessage()}");
        }
    }

    private function extractPageFromUrl(string $url): ?int
    {
        $parts = parse_url($url);
        if (!isset($parts['query'])) {
            return null;
        }
        parse_str($parts['query'], $query);
        return isset($query['page']) ? (int)$query['page'] : null;
    }

}
