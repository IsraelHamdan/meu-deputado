import { Deputado } from "./DeputadosResponse";
import { Despesas } from "./DespesasResponse";

export interface Pagination {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
}

export interface ApiResponseDeputados {
    success: boolean;
    data: Deputado[] | Deputado;
    pagination: Pagination;
}

export interface ApiResponseDespesas {
    success: boolean;
    pagination: Pagination;
    data: Despesas[] | Despesas;
}


export interface  ApiResponsePartidos {
    success: boolean;
    data: {
        id: number;
        nome: string;
        sigla: string;
        uri: string;
    }
    pagination: Pagination;
}

