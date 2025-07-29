"use client";
import { useDespesas } from "@/hooks/use-deputados";
import {
  Stack,
  Text,
  Spinner,
  Input,
  Table,
  Pagination,
  ButtonGroup,
  IconButton,
} from "@chakra-ui/react";
import { useState, useMemo } from "react";
import { Despesas } from "@/interfaces/DespesasResponse";
import { LuChevronLeft, LuChevronRight } from "react-icons/lu";
import { use } from "react";

interface PageProps {
  params: Promise<{ deputadoId: string }>;
}

export default function DespesasDashboard(props: PageProps) {
  const { deputadoId } = use(props.params);
  const { data, isLoading, isError } = useDespesas(deputadoId);
  const [initialDate, setInitialDate] = useState<Date | null>(null);
  const [finalDate, setFinalDate] = useState<Date | null>(null);
  const [ano, setAno] = useState<number | null>(null);
  const [valorLiquido, setValorLiquido] = useState<number | null>(null);
  const [currentPage, setCurrentPage] = useState<number>(1);

  const despesas: Despesas[] =
    data?.data && Array.isArray(data.data) ? data.data : [];

  console.log("🚀 ~ DespesasDashboard ~ despesas:", despesas);

  const despesasFiltradas = useMemo(() => {
    return despesas.filter((item) => {
      const dataDocumento = item.dataDocumento
        ? new Date(item.dataDocumento)
        : null;
      const dataDentroDoIntervalo =
        (!initialDate ||
          (dataDocumento !== null && dataDocumento >= initialDate)) &&
        (!finalDate || (dataDocumento !== null && dataDocumento <= finalDate));

      const anoCorreto = !ano || item.ano === ano;
      const valorCorreto =
        valorLiquido === null || (item.valorLiquido ?? 0) >= valorLiquido;

      return dataDentroDoIntervalo && anoCorreto && valorCorreto;
    });
  }, [despesas, initialDate, finalDate, ano, valorLiquido]);

  if (isLoading) {
    console.log("🚀 ~ DespesasDashboard ~ isLoading:", isLoading);
    return (
      <Stack align="center" py={10}>
        <Spinner size="lg" />
        <Text>Carregando despesas...</Text>
      </Stack>
    );
  }

  if (isError) {
    console.log("🚀 ~ DespesasDashboard ~ isError:", isError);
    return (
      <Stack align="center" py={10}>
        <Text color="red.500">Erro ao carregar despesas</Text>
      </Stack>
    );
  }

  const pagination = data?.pagination;
  const formatBRL = (value: number) =>
    new Intl.NumberFormat("pt-BR", {
      style: "currency",
      currency: "BRL",
    }).format(value);

  const formatDate = (iso: string) =>
    new Intl.DateTimeFormat("pt-BR", {
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
    }).format(new Date(iso));

  return (
    <Stack width="full" gap="5" p={6} align="center" direction="column">
      {/* Filtros */}
      <Stack
        direction={{ base: "column", md: "row" }}
        justify="center"
        align="center">
        <Input
          type="date"
          onChange={(e) => setInitialDate(new Date(e.target.value))}
          placeholder="Data inicial"
        />
        <Input
          type="date"
          onChange={(e) => setFinalDate(new Date(e.target.value))}
          placeholder="Data final"
        />
        <Input
          type="number"
          placeholder="Ano"
          onChange={(e) => setAno(Number(e.target.value))}
        />
        <Input
          type="number"
          placeholder="Valor Mínimo"
          onChange={(e) => setValorLiquido(Number(e.target.value))}
        />
      </Stack>

      {/* Tabela */}
      <Table.Root size="sm">
        <Table.Header>
          <Table.Row bg="gray.800">
            <Table.ColumnHeader color="gray.300">Ano</Table.ColumnHeader>
            <Table.ColumnHeader color="gray.300">
              CNPJ Fornecedor
            </Table.ColumnHeader>
            <Table.ColumnHeader color="gray.300">
              Nome Fornecedor
            </Table.ColumnHeader>
            <Table.ColumnHeader color="gray.300">
              Valor Líquido
            </Table.ColumnHeader>
            <Table.ColumnHeader color="gray.300">Parcela</Table.ColumnHeader>
            <Table.ColumnHeader color="gray.300">
              Tipo de Despesa
            </Table.ColumnHeader>
            <Table.ColumnHeader color="gray.300">
              Ressarcimento
            </Table.ColumnHeader>
            <Table.ColumnHeader color="gray.300">Cód. Lote</Table.ColumnHeader>
            <Table.ColumnHeader color="gray.300">
              Cód. Tipo Documento
            </Table.ColumnHeader>
            <Table.ColumnHeader color="gray.300">
              Nota Fiscal
            </Table.ColumnHeader>
          </Table.Row>
        </Table.Header>

        <Table.Body>
          {despesasFiltradas.map((item, index) => (
            <Table.Row
              key={item.codDocumento}
              bg={index % 2 === 0 ? "gray.50" : "white"}
              _hover={{ bg: "gray.400" }} 
            >
              <Table.Cell>{item.ano}</Table.Cell>
              <Table.Cell>{item.cnpjCpfFornecedor}</Table.Cell>
              <Table.Cell>{item.nomeFornecedor}</Table.Cell>
              <Table.Cell color="green.500" fontWeight="bold">
                R$ {formatBRL(item.valorLiquido ?? 0)}
              </Table.Cell>
              <Table.Cell>{item.parcela}</Table.Cell>
              <Table.Cell>{item.tipoDespesa}</Table.Cell>
              <Table.Cell>{item.numRessarcimento}</Table.Cell>
              <Table.Cell>{item.codLote}</Table.Cell>
              <Table.Cell>{item.codTipoDocumento}</Table.Cell>
              <Table.Cell>{item.urlDocumento}</Table.Cell>
            </Table.Row>
          ))}
        </Table.Body>
      </Table.Root>

      <Pagination.Root
        count={pagination?.total}
        pageSize={pagination?.per_page}
        page={pagination?.current_page}
        onPageChange={(e) => setCurrentPage(e.page)}>
        <ButtonGroup variant="solid" size="sm" wrap="wrap">
          <Pagination.PrevTrigger asChild>
            <IconButton aria-label="Anterior">
              <LuChevronLeft />
            </IconButton>
          </Pagination.PrevTrigger>
          <Pagination.Items
            render={(page) => (
              <IconButton variant={{ base: "solid", _selected: "surface" }}>
                {page.value}
              </IconButton>
            )}></Pagination.Items>
          <Pagination.NextTrigger asChild>
            <IconButton aria-label="Próxima">
              <LuChevronRight />
            </IconButton>
          </Pagination.NextTrigger>
        </ButtonGroup>
      </Pagination.Root>
    </Stack>
  );
}
