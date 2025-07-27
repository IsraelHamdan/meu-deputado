import React, { useState, useEffect } from 'react';
import { Head } from '@inertiajs/react';
import { router } from '@inertiajs/react';
import {useDeputadoByName, useDeputados, useDeputado} from '@/hooks/use-deputados';
import DeputadoCard from '@/components/DeputadoCard';
import { useFindPartidoByName } from '@/hooks/use-partidos';
import { Deputado } from '@/interfaces/DeputadosResponse';

interface DeputadosProps {
    title: string;
}

export default function Deputados() {
    const [page, setPage] = useState(1);

    const [deputadoId, setSelectedDeputadoId] = useState<number | null>(null);
    const [selectedPartidoId, setSelectedPartidoId] = useState<number | null>(0);

    const [nomeDeputado, setNomeDeputado] = useState<string>('');
    const [partidoNome, setPartidoNome] = useState<string>('');
    const [pageNomeBusca, setPageNomeBusca] = useState<number>(1);

    const deputadosQuery = useDeputados(page);
    const deputadosNameQuery = useDeputadoByName(nomeDeputado, pageNomeBusca);
    const partidosQuery =  useFindPartidoByName(partidoNome)

    useEffect(() => {
        setPageNomeBusca(1);
    }, [nomeDeputado]);

    useEffect(() => {
        setPage(1);
    }, [nomeDeputado, partidoNome]);

    const getActiveData = () => {
        if (nomeDeputado && deputadosNameQuery.data) {
            return {
                data: deputadosNameQuery.data,
                isLoading: deputadosNameQuery.isLoading,
                isError: deputadosNameQuery.isError,
                type: 'deputados'
            };
        }

        if (partidoNome && partidosQuery.data) {
            return {
                data: partidosQuery.data,
                isLoading: partidosQuery.isLoading,
                isError: partidosQuery.isError,
                type: 'partidos'
            };
        }

        return {
            data: deputadosQuery.data,
            isLoading: deputadosQuery.isLoading,
            isError: deputadosQuery.isError,
            type: 'deputados'
        };
    };
    // @ts-ignore

    const activateData = getActiveData();

    const handleDeputadoClick = (deputado: Deputado) => {
        setSelectedDeputadoId(deputado.id);
        setSelectedPartidoId(null); // fecha card de partido se estiver aberto
    };

    const handlePartidoClick = (partido: Partido) => {
        setSelectedPartidoId(partido.id);
        setSelectedDeputadoId(null); // fecha card de deputado se estiver aberto
    };

    const handleVerDespesas = (deputadoId: number) => {
        router.visit(`/despesas?deputado_id=${deputadoId}`);
    };

    const clearSearch = () => {
        setNomeDeputado('');
        setPartidoNome('');
        setSelectedDeputadoId(null);
        setSelectedPartidoId(null);
        setPage(1);
        setPageNomeBusca(1);
    };

    if(activateData.isLoading) return (
        <>
            <Head title={title} />
            <div className="container mt-4">
                <div className="text-center">
                    <div className="spinner-border text-primary" role="status">
                        <span className="visually-hidden">Carregando...</span>
                    </div>
                    <p className="mt-2">Carregando dados...</p>
                </div>
            </div>
        </>
    )

    if ()
}
