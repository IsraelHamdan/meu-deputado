<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $camara_id
 * @property string $nome
 * @property string|null $email
 * @property int $id_legislatura
 * @property string $sigla_partido
 * @property string $sigla_uf
 * @property string $uri
 * @property string $uri_partido
 * @property string $url_foto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Despesa> $depesas
 * @property-read int|null $depesas_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deputado newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deputado newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deputado query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deputado whereCamaraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deputado whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deputado whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deputado whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deputado whereIdLegislatura($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deputado whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deputado whereSiglaPartido($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deputado whereSiglaUf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deputado whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deputado whereUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deputado whereUriPartido($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Deputado whereUrlFoto($value)
 * @mixin \Eloquent
 */
class Deputado extends Model
{
    protected $table = 'deputados';

    protected $fillable = [
        'camara_id',
        'nome',
        'email',
        'id_legislatura',
        'sigla_partido',
        'sigla_uf',
        'uri',
        'uri_partido',
        'url_foto'
    ];

    public function depesas()
    {
        return $this->hasMany(Despesa::class);
    }
}
