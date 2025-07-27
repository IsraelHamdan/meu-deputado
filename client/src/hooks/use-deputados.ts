import { useQuery, useQueries, UseQueryResult } from '@tanstack/react-query';
import axios from 'axios';
import {ApiResponseDeputados} from '@/interfaces/ApiResponse';

const baseUrl = 'http://127.0.0.1:8000/api';

// busca todos os deputados

const fetchDeputados = async (page:number) => {
    const {data} = await axios.get(`${baseUrl}/deputados?page=${page}`);
    console.log(data);
    if(data) return data;
}
export function useDeputados(page:number): UseQueryResult<ApiResponseDeputados> {
    return useQuery<ApiResponseDeputados>({
        queryKey:['Deputados', page],
        queryFn: () => fetchDeputados(page),
        staleTime: 1000 * 60 * 5,
    })
}

// busca um deputado por seu id
const findDeputado = async (id:number) => {
    const {data} = await axios.get(`${baseUrl}/deputados/${id}`);
    if(data) return data;
}

export function useDeputado(id:number): UseQueryResult<ApiResponseDeputados> {
    return useQuery<ApiResponseDeputados>({
        queryKey:['Deputado', id],
        queryFn: () => findDeputado(id),
        enabled: !!id
    })
}

// busca um deputado por seu nome

const findDeputadoByName = async (nome:string, page: number) => {
    const {data} = await axios.get(`${baseUrl}/deputados/nome/${nome}?page=${page}`);
    if(data) return data;
}

export function useDeputadoByName(nome:string, page: number): UseQueryResult<ApiResponseDeputados> {
    return useQuery<ApiResponseDeputados>({
        queryKey:['DeputadoByName', nome],
        queryFn: () => findDeputadoByName(nome, page),
        enabled: nome.length > 2,
        staleTime: 1000 * 60 * 5,
    })
}

export function useAllDeputados() {
    const firstPageQuery = useDeputados(1);
    const totalPages: number = firstPageQuery.data?.pagination.last_page || 0;

    const allPagesQueries = useQueries({
        queries: Array.from({ length: totalPages }, (_, index: number) => ({
            queryKey: ['Deputados', index + 1],
            queryFn: () => fetchDeputados(index + 1),
            staleTime: 1000 * 60 * 5,
            enabled: totalPages > 0, // só executar quando soubermos quantas páginas são
        })),
    });

    const allData = allPagesQueries
        .filter(query => query.data)
        .flatMap(query => query.data?.data || []);

    const isLoading:boolean = firstPageQuery.isLoading || allPagesQueries.some(query => query.isLoading);
    const isError: boolean = firstPageQuery.isError || allPagesQueries.some(query => query.isError);

    return {
        data: allData,
        isLoading,
        isError,
        totalPages,
        pagination: firstPageQuery.data?.pagination
    };
}


