"use-client";

import React from "react";
import { HStack, Button } from "@chakra-ui/react";
import Link from "next/link";
const HeaderComponent: React.FC = () => {
  return (
    <HStack
      p={4}
      bg="gray.100"
      justify="center"
      direction={{ base: "column", md: "row" }}
      gap="10">
      <Button asChild colorScheme="teal" variant="solid">
        <Link href="/partidos">Partidos</Link>
      </Button>
      <Button asChild colorScheme="teal" variant="solid">
        <Link href="/depudatos">Deputados</Link>
      </Button>
    </HStack>
  );
};

export default HeaderComponent;
