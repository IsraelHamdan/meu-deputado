<?php

namespace App\Http\Controllers;

use App\Services\DespesaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DespesaController extends Controller
{
    private DespesaService $despesaService;

    public function __construct(DespesaService $despesaService)
    {
        $this->despesaService = $despesaService;
    }

    public function listarTodos(int $deputadoId, Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 30);
            $despesas = $this->despesaService->listarTodos($perPage, $deputadoId);
            return response()->json([
                'data' => $despesas->items(),
                'pagination' => [
                    'current_page' => $despesas->currentPage(),
                    'last_page' => $despesas->lastPage(),
                    'per_page' => $despesas->perPage(),
                    'total' => $despesas->total(),
                    'from' => $despesas->firstItem(),
                    'to' => $despesas->lastItem(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar deputados',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
