import {useQuery} from '@tanstack/react-query';
import axios from 'axios';
import { ApiResponsePartidos } from '@/interfaces/ApiResponse';

const baseUrl = 'http://127.0.0.1:8000/api';

// Busca todos os partidos

const findPartidos = async (page: number) => {
    const {data} = await axios.get(`${baseUrl}/partidos?page=${page}`);
    if(data) return data
}

export function usePartidos(page: number) {
    return useQuery<ApiResponsePartidos>({
        queryKey: ['Partidos', page],
        queryFn: () => findPartidos(page),
        staleTime: 1000 * 60 * 5,
    })
}

//busca por ID

const findById = async (id: number) => {
    const {data} = await axios.get(`${baseUrl}/partidos/buscar/id/${id}`);
    if(data) return data;
}

export function useFindPardidoById (id: number) {
    return useQuery<ApiResponsePartidos>({
        queryKey: ['PartidoById', id],
        queryFn: () => findById(id),
        staleTime: 1000 * 60 * 5,
    })
}

// busca por nome

const findByName = async (nome: string) => {
    const {data} = await axios.get(`${baseUrl}/partidos/buscar/nome/${nome}`);
    if(data) return data;
}

export function useFindPartidoByName (nome: string) {
    return useQuery<ApiResponsePartidos>({
        queryKey: ['PartidoByName', nome],
        queryFn: () => findByName(nome),
        staleTime: 1000 * 60 * 5,
    })
}

// busca por Sigla
const findBySigla = async (sigla: string) => {
    const {data} = await axios.get(`${baseUrl}/partidos//buscar/sigla/${sigla}`);
    if(data) return data;
}

export function useFindPartidoBySigla (sigla: string) {
    return useQuery<ApiResponsePartidos>({
        queryKey: ['PartidoBySigla', sigla],
        queryFn: () => findBySigla(sigla),
        staleTime: 1000 * 60 * 5,
    })
}
