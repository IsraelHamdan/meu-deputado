<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $deputado_id
 * @property int $ano
 * @property string $cnpj_cpf_fornecedor
 * @property int $cod_documento
 * @property int $cod_lote
 * @property int $cod_tipo_documento
 * @property string|null $data_documento
 * @property int $mes
 * @property string $nome_fornecedor
 * @property string $num_documento
 * @property string $num_ressarcimento
 * @property int $parcela
 * @property string $tipo_despesa
 * @property string $tipo_documento
 * @property string|null $url_documento
 * @property string $valor_documento
 * @property string $valor_glosa
 * @property string $valor_liquido
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Deputado $deputado
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereAno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereCnpjCpfFornecedor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereCodDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereCodLote($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereCodTipoDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereDataDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereDeputadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereMes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereNomeFornecedor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereNumDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereNumRessarcimento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereParcela($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereTipoDespesa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereTipoDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereUrlDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereValorDocumento($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereValorGlosa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Despesa whereValorLiquido($value)
 * @mixin \Eloquent
 */
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
