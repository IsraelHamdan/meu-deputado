<?php

namespace App\Services;

use App\Models\Deputado;
use App\Models\Partido;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PartidoService
{
    public function listarTodos(int $perPage = 15): LengthAwarePaginator
    {
        return Partido::orderBy('nome')->paginate($perPage);
    }

    public function buscarPorId(int $id): Partido
    {
        return Partido::findOrFail($id);
    }

    public function buscarPorNome(string $nome, int $perPage): LengthAwarePaginator
    {
        return Partido::where('nome', 'LIKE', "%{$nome}%")
            ->orderBy('nome', 'ASC')
            ->paginate($perPage);
    }

    public function buscaPorSigla(string $sigla): Partido
    {
        return Partido::where('sigla', strtoupper($sigla))->firstOrFail();
    }


}
