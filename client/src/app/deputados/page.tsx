/* eslint-disable @typescript-eslint/no-unused-vars */
"use client";

import { useState } from "react";
import {
  Table,
  ButtonGroup,
  IconButton,
  Pagination,
  Stack,
  Heading,
  Text,
  Image,
  Badge,
  Spinner,
  Alert,
  Button,
} from "@chakra-ui/react";
import { Deputado } from "@/interfaces/DeputadosResponse";
import { useDeputados } from "@/hooks/use-deputados";
import { ApiResponseDeputados } from "@/interfaces/ApiResponse";
import Link from "next/link";
import { LuChevronLeft, LuChevronRight } from "react-icons/lu";
import DeputadoCard from "@/components/DeputadoCard";

export default function DeputadosDahboard() {
  const [currentPage, setCurrentPage] = useState<number>(1);
  const { data, isLoading, isError, isSuccess } = useDeputados(currentPage);
  const [selectedPartido, setSelectedPartido] = useState<string | null>(null);
  const [deputadoId, setDeputadoId] = useState<string | null>(null);
  const [nomeDeputado, setNomeDeputado] = useState<string>("");

  if (isLoading) {
    return (
      <Stack align="center" py={10}>
        <Spinner size="lg" />
        <Text>Carregando deputados...</Text>
      </Stack>
    );
  }

  if (isError) {
    return (
      <Alert.Root status="error">
        <Alert.Title>Erro ao carregar deputados</Alert.Title>
        <Alert.Description>
          Não foi possível carregar os dados dos deputados.
        </Alert.Description>
      </Alert.Root>
    );
  }
  if (!data || !data.data) {
    return (
      <Alert.Root status="info">
        <Alert.Title>Nenhum deputado encontrado</Alert.Title>
      </Alert.Root>
    );
  }

  const deputados = Array.isArray(data.data) ? data.data : [data.data];
  const pagination = data.pagination;

  const despesasDashborad = `/deputados/${deputadoId}`;

  const handleDeputadoClick = (deputado: Deputado) => {
    console.log(`Deputado id: ${deputadoId}`);
    setDeputadoId(String(deputado.id));
    setSelectedPartido(null);
  };

  return (
    <Stack width="full" gap="5" p={6} align="center" direction="column">
      <Heading size="xl">Deputados dashboard</Heading>
      <Table.Root size="sm" variant="outline" interactive>
        <Table.Header>
          <Table.Row>
            <Table.ColumnHeader>Nome</Table.ColumnHeader>
            <Table.ColumnHeader>email</Table.ColumnHeader>
            <Table.ColumnHeader>Gastos</Table.ColumnHeader>
            <Table.ColumnHeader>Partido</Table.ColumnHeader>
            <Table.ColumnHeader>Legislatura</Table.ColumnHeader>
            <Table.ColumnHeader>UF</Table.ColumnHeader>
          </Table.Row>
        </Table.Header>
        <Table.Body>
          {deputados.map((deputado: Deputado) => (
            <Table.Row key={deputado.id} _hover={{ bg: "gray.300" }}>
              <Table.Cell>
                <Button
                  variant="solid"
                  onClick={() => handleDeputadoClick(deputado)}>
                  {deputado.nome}
                </Button>
              </Table.Cell>
              <Table.Cell>
                <Text fontWeight="medium">{deputado.email}</Text>
              </Table.Cell>
              <Table.Cell>
                <Button>
                  <Link href={`/deputados/${deputado.id}`}>
                    <Button as="a">Ver Gastos</Button>
                  </Link>
                </Button>
              </Table.Cell>
              <Table.Cell>
                <Text fontWeight="medium">{deputado.sigla_partido}</Text>
              </Table.Cell>
              <Table.Cell>
                <Text fontWeight="semibold">{deputado.id_legislatura}</Text>
              </Table.Cell>
              <Table.Cell>
                <Text fontWeight="semibold">{deputado.sigla_uf}</Text>
              </Table.Cell>
            </Table.Row>
          ))}
        </Table.Body>
      </Table.Root>
      <Pagination.Root
        count={pagination.total}
        pageSize={pagination.per_page}
        page={pagination.current_page}
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
      {deputadoId !== null && (
        <DeputadoCard
          deputadoId={deputadoId}
          onClose={() => setDeputadoId(null)}
        />
      )}
    </Stack>
  );
}
