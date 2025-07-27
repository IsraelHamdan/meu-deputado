import { useQuery, useQueries, UseQueryResult } from "@tanstack/react-query";
import {
  ApiResponseDeputados,
  ApiResponseDespesas,
} from "@/interfaces/ApiResponse";
import axios from "axios";

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
const findDeputado = async (id: number) => {
  const { data } = await axios.get(`${baseUrl}/deputados/buscar/${id}`);
  console.log("🚀 ~ findDeputado ~ data:", data);
  if (data) return data;
};

export function useDeputado(id: number): UseQueryResult<ApiResponseDeputados> {
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
const fetchDespesas = async (deputadoId: number) => {
  const { data } = await axios.get(`${baseUrl}/despesas/findAll/${deputadoId}`);
  if (data) return data;
};

export function useDespesas(
  deputadoId: number
): UseQueryResult<ApiResponseDespesas> {
  return useQuery<ApiResponseDespesas>({
    queryKey: ["Despesas", deputadoId],
    queryFn: () => fetchDeputados(deputadoId),
    staleTime: 1000 * 60 * 5,
  });
}
