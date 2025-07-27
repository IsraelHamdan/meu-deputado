<?php

namespace App\Http\Controllers;

use App\Services\PartidoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Psy\Util\Json;

class PartidoController extends Controller
{
    public int $pagina;
    public int $itensPorPagina;
    public static int $totalGlobalProcessados = 0;

    protected PartidoService $partidoService;
    public function __construct(PartidoService $partidoService)
    {
        $this->partidoService = $partidoService;
    }

    public function index(Request $request):JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $partidos = $this->partidoService->listarTodos($perPage);
            return response()->json([
                'data' => $partidos->items(),
                'pagination' => [
                    'current_page' => $partidos->currentPage(),
                    'last_page' => $partidos->lastPage(),
                    'per_page' => $partidos->perPage(),
                    'total' => $partidos->total(),
                    'from' => $partidos->firstItem(),
                    'to' => $partidos->lastItem(),
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

    public function buscaPartidoPorId(int $id): JsonResponse
    {
        try {
            $partido = $this->partidoService->buscarPorId($id);
            return response()->json([
                'success' => true,
                'data' => $partido
            ]);
        }  catch (\Illuminate\Validation\ValidationException $e) {
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

    public function buscaPorNome (Request $request, string $nome): JsonResponse
    {
        try {
            if (strlen($nome) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'O nome do partido deve ter pelo menos 2 caracteres.'
                ], 422);
            }
            $perPage = $request->get('per_page', 15);
            $partidos = $this->partidoService->buscarPorNome($nome, $perPage);
            return response()->json([
                'success' => true,
                'data' => $partidos->items(),
                'pagination' => [
                    'current_page' => $partidos->currentPage(),
                    'last_page' => $partidos->lastPage(),
                    'per_page' => $partidos->perPage(),
                    'total' => $partidos->total(),
                    'from' => $partidos->firstItem(),
                    'to' => $partidos->lastItem(),
                ]
            ]);
        }  catch (\Illuminate\Validation\ValidationException $e) {
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

    public function buscaPorSigla(string $sigla): JsonResponse
    {
        try {
            if (strlen($sigla) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'A sigla deve ter pelo menos 2 caracteres.'
                ], 422);
            }
            $partidos = $this->partidoService->buscaPorSigla($sigla);

            if(!$partidos) {
                return response()->json([
                    'success' => false,
                    'message' => 'Partido não encontrado.'
                ]);
            }

            return response()->json([
                'success' => true,
                'data'=> $partidos,
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
