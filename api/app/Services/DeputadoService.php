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
        return Deputado::with('partido')->findOrFail($id);
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
        return Deputado::with('nome')
            ->where('nome', 'LIKE', "%{$nome}%")
            ->orderBy('nome')
            ->paginate($perPage);
    }

    /**
     * Busca todos os deputados de um partido específico pelo ID do partido
     *
     * @param string|int $partidoId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function buscarPorPartidoId($partidoId, int $perPage = 15): LengthAwarePaginator
    {
        return Deputado::with('partido')
            ->where('partido_id', $partidoId)
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Busca todos os deputados de um partido pelo nome do partido (busca parcial)
     *
     * @param string $nomePDeputado
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function buscarPorNomePartido(string $nomePDeputado, int $perPage = 15): LengthAwarePaginator
    {
        return Deputado::with('partido')
            ->whereHas('partido', function ($query) use ($nomePDeputado) {
                $query->where('nome', 'LIKE', "%{$nomePDeputado}%");
            })
            ->orderBy('name')
            ->paginate($perPage);
    }


}
