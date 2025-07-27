<?php

namespace App\Http\Controllers;

use App\Services\DeputadoService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Inertia\Inertia;

class DeputadoController extends Controller
{
    protected DeputadoService $deputadoService;

    public function __construct(DeputadoService $deputadoService)
    {
        $this->deputadoService = $deputadoService;
    }

    /**
     * Lista todos os deputados de forma paginada
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request):JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $deputados = $this->deputadoService->listarTodos($perPage);

            return response()->json([
                'data' => $deputados->items(),
                'pagination' => [
                    'current_page' => $deputados->currentPage(),
                    'last_page' => $deputados->lastPage(),
                    'per_page' => $deputados->perPage(),
                    'total' => $deputados->total(),
                    'from' => $deputados->firstItem(),
                    'to' => $deputados->lastItem(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao buscar deputados',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Busca um deputado específico pelo ID
     *
     * @param string $id
     * @return JsonResponse
     */
    public function buscaDeputadoPorId(string $id): JsonResponse
    {
        try {
            $deputado = $this->deputadoService->buscarPorId($id);

            return response()->json([
                'success' => true,
                'data' => $deputado
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Deputado não encontrado'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar deputado',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Busca deputados pelo nome (busca parcial)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function buscarPorNome(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nome' => 'required|string|min:2'
            ]);

            $nome = $request->get('nome');
            $perPage = $request->get('per_page', 15);

            $deputados = $this->deputadoService->buscarPorNome($nome, $perPage);

            return response()->json([
                'success' => true,
                'data' => $deputados->items(),
                'pagination' => [
                    'current_page' => $deputados->currentPage(),
                    'last_page' => $deputados->lastPage(),
                    'per_page' => $deputados->perPage(),
                    'total' => $deputados->total(),
                    'from' => $deputados->firstItem(),
                    'to' => $deputados->lastItem(),
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar deputados por nome',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Busca deputados por ID do partido
     *
     * @param Request $request
     * @param string $partidoId
     * @return JsonResponse
     */
    public function buscarPorPartidoId(Request $request, string $partidoId): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $deputados = $this->deputadoService->buscarPorPartidoId($partidoId, $perPage);

            return response()->json([
                'success' => true,
                'data' => $deputados->items(),
                'pagination' => [
                    'current_page' => $deputados->currentPage(),
                    'last_page' => $deputados->lastPage(),
                    'per_page' => $deputados->perPage(),
                    'total' => $deputados->total(),
                    'from' => $deputados->firstItem(),
                    'to' => $deputados->lastItem(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar deputados por partido',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Busca deputados por nome do partido
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function buscarPorNomePartido(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nome_partido' => 'required|string|min:2'
            ]);

            $nomePartido = $request->get('nome_partido');
            $perPage = $request->get('per_page', 15);

            $deputados = $this->deputadoService->buscarPorNomePartido($nomePartido, $perPage);

            return response()->json([
                'success' => true,
                'data' => $deputados->items(),
                'pagination' => [
                    'current_page' => $deputados->currentPage(),
                    'last_page' => $deputados->lastPage(),
                    'per_page' => $deputados->perPage(),
                    'total' => $deputados->total(),
                    'from' => $deputados->firstItem(),
                    'to' => $deputados->lastItem(),
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dados inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao buscar deputados por nome do partido',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
