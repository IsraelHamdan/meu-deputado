import { Deputado } from "./DeputadosResponse";
import { Despesas } from "./DespesasResponse";

export interface PaginationType {
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
  pagination: PaginationType;
  despesasLink?: string;
}

export interface ApiResponseDespesas {
  success: boolean;
  pagination: PaginationType;
  data: Despesas[] | Despesas;
}

export interface ApiResponsePartidos {
  success: boolean;
  data: {
    id: number;
    nome: string;
    sigla: string;
    uri: string;
  };
  pagination: PaginationType;
}
