import { useFindPartidoBySigla } from "@/hooks/use-partidos";
import { useState } from "react";

export default function PartidoCard(partidoSigla: string) {
  const [page, setPage] = useState(1);
  const { data, isLoading, isError } = useFindPartidoBySigla(partidoSigla);
}
