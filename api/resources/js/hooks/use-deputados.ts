import {useQuery} from '@tanstack/react-query';
import axios from 'axios';
import {ApiResponseDeputados} from '@/interfaces/ApiResponse';

const baseUrl = 'http://127.0.0.1:8000/api';

// busca todos os deputados

const fetchDeputados = async (page:number) => {
    const {data} = await axios.get(`${baseUrl}/deputados?page=${page}`);
    console.log(data);
    if(data) return data;
}
export function useDeputados(page:number) {
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

export function useDeputado(id:number) {
    return useQuery<ApiResponseDeputados>({
        queryKey:['Deputado', id],
        queryFn: () => findDeputado(id),
    })
}

// busca um deputado por seu nome

const findDeputadoByName = async (nome:string) => {
    const {data} = await axios.get(`${baseUrl}/deputados/nome/${nome}`);
    if(data) return data;
}

export function useDeputadoByName(nome:string) {
    return useQuery<ApiResponseDeputados>({
        queryKey:['DeputadoByName', nome],
        queryFn: () => findDeputadoByName(nome),
    })
}

