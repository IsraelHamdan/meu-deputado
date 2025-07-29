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

export interface RawDespesa {
  ano: number;
  cnpj_cpf_fornecedor: string;
  cod_documento: number;
  cod_lote: number;
  cod_tipo_documento: number;
  data_documento: string;
  mes: number;
  nome_fornecedor: string;
  num_documento: string;
  num_ressarcimento: string | null;
  parcela: number;
  tipo_despesa: string;
  tipo_documento: string;
  url_documento: string;
  valor_documento: string;
  valor_glosa: string;
  valor_liquido: string;
}

export interface ApiResponseRaw {
  success: boolean;
  pagination: PaginationType;
  data: RawDespesa[];
}
