<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deputado extends Model
{
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
