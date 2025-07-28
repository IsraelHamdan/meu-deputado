// components/DeputadoCard.tsx
"use client";
import {
  Text,
  Image,
  Button,
  Stack,
  Spinner,
  Heading,
  Flex,
  Card,
} from "@chakra-ui/react";
import { useDeputado } from "@/hooks/use-deputados"; // adapte ao seu projeto
import { Deputado } from "@/interfaces/DeputadosResponse";
import Link from "next/link";

interface DeputadoCardProps {
  deputadoId: string;
  onClose: () => void;
}

export default function DeputadoCard({
  deputadoId,
  onClose,
}: DeputadoCardProps) {
  console.log(`Deputado ID no card: ${deputadoId}`);
  const { data, isLoading, isError } = useDeputado(deputadoId);

  if (isLoading) {
    return (
      <Flex justify="center" align="center" minH="200px">
        <Spinner size="lg" />
      </Flex>
    );
  }

  if (isError || !data?.data) {
    return <Text color="red.500">Erro ao carregar deputado.</Text>;
  }

  const deputado: Deputado = Array.isArray(data.data)
    ? data.data[0]
    : data.data;

  const despesasDashborad = `/desputados/${deputadoId}`;

  return (
    <Card.Root
      position="fixed"
      top="20%"
      left="50%"
      transform="translateX(-50%)"
      zIndex="popover"
      width="sm"
      shadow="xl"
      p={4}
      bg="white"
      colorPalette="gray">
      <Card.Header>
        <Heading size="md" color="#1e1e1e">
          {deputado.nome}
        </Heading>
      </Card.Header>
      <Card.Body>
        <Stack direction="row">
          <Image
            src={deputado.url_foto}
            alt={deputado.nome}
            boxSize="100px"
            borderRadius="full"
            objectFit="cover"
          />
          <Stack>
            <Text color="#1e1e1e">
              <strong>Email:</strong> {deputado.email}
            </Text>
            <Text color="#1e1e1e">
              <strong>Partido:</strong> {deputado.sigla_partido}
            </Text>
            <Text color="#1e1e1e">
              <strong>UF:</strong> {deputado.sigla_uf}
            </Text>
            <Text color="#1e1e1e">
              <strong>Legislatura:</strong> {deputado.id_legislatura}
            </Text>
          </Stack>
        </Stack>
      </Card.Body>
      <Card.Footer justifyContent="space-between">
        <Button colorScheme="blue">
          <Link href={despesasDashborad}>Ver Despesas</Link>
        </Button>
        <Button colorScheme="red" onClick={onClose}>
          Fechar
        </Button>
      </Card.Footer>
    </Card.Root>
  );
}
