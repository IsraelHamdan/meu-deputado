import { useQuery, useQueries, UseQueryResult } from "@tanstack/react-query";
import {
  ApiResponseDeputados,
  ApiResponseDespesas,
  ApiResponseRaw,
} from "@/interfaces/ApiResponse";
import axios from "axios";
import dynamic from "next/dynamic";
import { Despesas } from "@/interfaces/DespesasResponse";

const baseUrl = "http://127.0.0.1:8000/api";

// busca todos os deputados

const fetchDeputados = async (page: number) => {
  const { data } = await axios.get(`${baseUrl}/deputados?page=${page}`);
  if (data) return data;
};
export function useDeputados(
  page: number
): UseQueryResult<ApiResponseDeputados> {
  return useQuery<ApiResponseDeputados>({
    queryKey: ["Deputados", page],
    queryFn: () => fetchDeputados(page),
    staleTime: 1000 * 60 * 5,
  });
}

// busca um deputado por seu id
const findDeputado = async (id: string) => {
  const { data } = await axios.get(`${baseUrl}/deputados/buscar/${id}`);
  console.log("🚀 ~ findDeputado ~ data:", data);
  if (data) return data;
};

export function useDeputado(id: string): UseQueryResult<ApiResponseDeputados> {
  return useQuery<ApiResponseDeputados>({
    queryKey: ["Deputado", id],
    queryFn: () => findDeputado(id),
    enabled: !!id,
  });
}

// busca um deputado por seu nome

const findDeputadoByName = async (nome: string, page: number) => {
  const { data } = await axios.get(
    `${baseUrl}/deputados/nome/${nome}?page=${page}`
  );
  if (data) return data;
};

export function useDeputadoByName(
  nome: string,
  page: number
): UseQueryResult<ApiResponseDeputados> {
  return useQuery<ApiResponseDeputados>({
    queryKey: ["DeputadoByName", nome],
    queryFn: () => findDeputadoByName(nome, page),
    enabled: nome.length > 2,
    staleTime: 1000 * 60 * 5,
  });
}

// busca as despesas de determinado deputado
// hooks/useDespesas.ts
const fetchDespesas = async (deputadoId: string, page = 1) => {
  const resp = await axios.get<ApiResponseRaw>(
    `${baseUrl}/despesas/findAll/${deputadoId}?page=${page}`
  );
  // Supondo resp.data = { success, pagination, data: [ { snake_case… } ] }
  const raw = Array.isArray(resp.data.data) ? resp.data.data : [];
  const converted: Despesas[] = raw.map((item) => ({
    ano: item.ano,
    cnpjCpfFornecedor: item.cnpj_cpf_fornecedor,
    codDocumento: item.cod_documento,
    codLote: item.cod_lote,
    codTipoDocumento: item.cod_tipo_documento,
    dataDocumento: item.data_documento ? new Date(item.data_documento) : null,
    mes: item.mes,
    nomeFornecedor: item.nome_fornecedor,
    numDocumento: item.num_documento,
    numRessarcimento: item.num_ressarcimento || null,
    parcela: item.parcela,
    tipoDespesa: item.tipo_despesa,
    tipoDocumento: item.tipo_documento,
    urlDocumento: item.url_documento,
    valorDocumento:
      item.valor_documento !== null ? parseFloat(item.valor_documento) : null,
    valorGlosa: item.valor_glosa !== null ? parseFloat(item.valor_glosa) : null,
    valorLiquido:
      item.valor_liquido !== null ? parseFloat(item.valor_liquido) : null,
  }));

  return {
    success: resp.data.success,
    pagination: resp.data.pagination,
    data: converted,
  } as ApiResponseDespesas;
};

export function useDespesas(
  deputadoId: string,
  page = 1
): UseQueryResult<ApiResponseDespesas> {
  return useQuery<ApiResponseDespesas>({
    queryKey: ["Despesas", deputadoId, page],
    queryFn: () => fetchDespesas(deputadoId, page),
    staleTime: 1000 * 60 * 5,
  });
}
