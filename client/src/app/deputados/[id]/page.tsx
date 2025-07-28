"use client";
import { useDespesas } from "@/hooks/use-deputados";
import {
  Stack,
  Text,
  Heading,
  Spinner,
  Input,
  Table,
  Image,
  Card,
  Pagination,
  ButtonGroup,
  IconButton,
} from "@chakra-ui/react";
import { useState, useMemo } from "react";
import { Despesas } from "@/interfaces/DespesasResponse";
import { LuChevronLeft, LuChevronRight } from "react-icons/lu";

interface PageProps {
  params: {
    deputadoId: string;
  };
}
export default function DespesasDashboard({ params }: PageProps) {
  const { data, isLoading, isError } = useDespesas(params.deputadoId);
  const [initialDate, setInitialDate] = useState<Date | null>(null);
  const [finalDate, setFinalDate] = useState<Date | null>(null);
  const [ano, setAno] = useState<number | null>(null);
  const [valorLiquido, setValorLiquido] = useState<number | null>(null);
  const [currentPage, setCurrentPage] = useState<number>(1);

  const despesas: Despesas[] = Array.isArray(data) ? data : [];

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
    return (
      <Stack align="center" py={10}>
        <Spinner size="lg" />
        <Text>Carregando despesas...</Text>
      </Stack>
    );
  }

  if (isError) {
    return (
      <Stack align="center" py={10}>
        <Text color="red.500">Erro ao carregar despesas</Text>
      </Stack>
    );
  }

  const pagination = data?.pagination;

  return (
    <Stack width="full" gap={8} p={6}>
      {/* Card com informações do deputado */}
      {/* <Card.Root maxW="sm" mx="auto" boxShadow="xl" bg="white" p={4}>
        <Card.Header textAlign="center">
          <Image
            src={params.url_foto}
            alt={params.nomeDeputado}
            boxSize="100px"
            borderRadius="full"
            mx="auto"
            mb={4}
          />
          <Heading size="md">{params.nomeDeputado}</Heading>
          <Text color="gray.600">{params.sigla_partido}</Text>
        </Card.Header>
      </Card.Root> */}

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
          <Table.Row>
            <Table.ColumnHeader>Ano</Table.ColumnHeader>
            <Table.ColumnHeader>CNPJ Fornecedor</Table.ColumnHeader>
            <Table.ColumnHeader>Nome Fornecedor</Table.ColumnHeader>
            <Table.ColumnHeader>Valor Líquido</Table.ColumnHeader>
            <Table.ColumnHeader>Parcela</Table.ColumnHeader>
            <Table.ColumnHeader>Tipo de Despesa</Table.ColumnHeader>
            <Table.ColumnHeader>Ressarcimento</Table.ColumnHeader>
            <Table.ColumnHeader>Cód. Lote</Table.ColumnHeader>
            <Table.ColumnHeader>Cód. Tipo Documento</Table.ColumnHeader>
            <Table.ColumnHeader>Nota Fiscal</Table.ColumnHeader>
          </Table.Row>
        </Table.Header>
        <Table.Body>
          {despesasFiltradas.map((item) => (
            <Table.Row key={item.codDocumento}>
              <Table.ColumnHeader>{item.ano}</Table.ColumnHeader>
              <Table.ColumnHeader>{item.cnpjCpfFornecedor}</Table.ColumnHeader>
              <Table.ColumnHeader>{item.nomeFornecedor}</Table.ColumnHeader>
              <Table.ColumnHeader>
                R${" "}
                {item.valorLiquido !== null
                  ? item.valorLiquido.toFixed(2)
                  : "0.00"}
              </Table.ColumnHeader>

              <Table.ColumnHeader>{item.parcela}</Table.ColumnHeader>
              <Table.ColumnHeader>{item.tipoDespesa}</Table.ColumnHeader>
              <Table.ColumnHeader>
                {item.numRessarcimento || "-"}
              </Table.ColumnHeader>
              <Table.ColumnHeader>{item.codLote}</Table.ColumnHeader>
              <Table.ColumnHeader>{item.codTipoDocumento}</Table.ColumnHeader>
              <Table.ColumnHeader>
                <a
                  target="_blank"
                  rel="noopener noreferrer"
                  style={{ color: "#3182CE" }}>
                  {item.urlDocumento}
                </a>
              </Table.ColumnHeader>
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
