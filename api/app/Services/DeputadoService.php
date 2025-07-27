<?php

namespace App\Services;

use App\Models\Deputado;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DeputadoService
{
    /**
     * Lista todos os deputados de forma paginada
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function listarTodos(int $perPage = 15): LengthAwarePaginator
    {
        return Deputado::
            orderBy('nome')
            ->paginate($perPage);
    }

    /**
     * Busca um deputado específico pelo ID
     *
     * @param string|int $id
     * @return Deputado
     * @throws ModelNotFoundException
     */
    public function buscarPorId($id): Deputado
    {
        return Deputado::findOrFail($id);
    }

    /**
     * Busca deputados pelo nome (busca parcial)
     *
     * @param string $nome
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function buscarPorNome(string $nome, int $perPage = 15): LengthAwarePaginator
    {
        return Deputado::where('nome', 'LIKE', "%{$nome}%")
            ->where('nome', 'LIKE', "%{$nome}%")
            ->orderBy('nome')
            ->paginate($perPage);
    }






}
