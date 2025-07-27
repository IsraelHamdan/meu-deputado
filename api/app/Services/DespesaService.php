<?php

namespace App\Services;

use App\Models\Despesa;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DespesaService
{
    /**
     * Lista todos as despesas do deputado de forma paginada
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function listarTodos(int $perPage = 30, int $deputadoId): LengthAwarePaginator
    {
        return Despesa::select([
            'ano',
            'cnpj_cpf_fornecedor',
            'cod_documento',
            'cod_lote',
            'cod_tipo_documento',
            'data_documento',
            'mes',
            'nome_fornecedor',
            'num_documento',
            'num_ressarcimento',
            'parcela',
            'tipo_despesa',
            'tipo_documento',
            'url_documento',
            'valor_documento',
            'valor_glosa',
            'valor_liquido',
        ])
            ->where('deputado_id', $deputadoId)
            ->orderByDesc('data_documento')
            ->paginate($perPage);
    }

}
