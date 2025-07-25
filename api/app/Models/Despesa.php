<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    protected $fillable = [
        'deputado_id',
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
        'valor_liquido'
    ];

    public function deputado()
    {
        return $this->belongsTo(Deputado::class);
    }
}
