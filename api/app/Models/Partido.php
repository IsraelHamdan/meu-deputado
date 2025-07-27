<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Deputado> $deputados
 * @property-read int|null $deputados_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partido newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partido newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Partido query()
 * @mixin \Eloquent
 */
class Partido extends Model
{
    protected $table = 'partidos';
    protected $fillable = ['id','nome' , 'sigla', 'uri'];



    public $incrementing = false;
}
