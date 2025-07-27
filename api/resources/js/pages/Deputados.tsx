import React, { useState, useEffect } from 'react';

import { Deputado } from '@/types/api';
import {useDeputadoByName, useDeputados, useDeputado} from '@/hooks/use-deputados';
import DeputadoCard from '@/components/DeputadoCard';

export default function Deputados() {
    const [page, setPage] = useState(1);
    const [selectedId, setSelectedId] = useState<number | null>(null);

    // Estados para as buscas
    const [nomeBusca, setNomeBusca] = useState('');
    const [partidoBusca, setPartidoBusca] = useState('');


    if (isLoading) return <p>Carregando...</p>;
    if (isError || !data) return <p>Erro ao carregar dados</p>;


    // Resetar página para 1 sempre que a busca mudar
    useEffect(() => {
        setPage(1);
    }, [nomeBusca, partidoBusca]);

    // @ts-ignore
    return (
        <div className="container mt-4">
            <h2>Lista de Deputados</h2>
            {/* Inputs de busca */}
            <div className="row mb-3">
                <div className="col-md-6 mb-2">
                    <input
                        type="text"
                        className="form-control"
                        placeholder="Buscar deputado por nome"
                        value={nomeBusca}
                        onChange={e => {
                            setNomeBusca(e.target.value);
                            setPartidoBusca(''); // limpa busca por partido se estiver buscando por nome
                            setSelectedId(null);
                        }}
                    />
                </div>
                <div className="col-md-6 mb-2">
                    <input
                        type="text"
                        className="form-control"
                        placeholder="Buscar deputados por partido"
                        value={partidoBusca}
                        onChange={e => {
                            setPartidoBusca(e.target.value);
                            setNomeBusca(''); // limpa busca por nome se estiver buscando por partido
                            setSelectedId(null);
                        }}
                    />
                </div>
            </div>

            {isLoading && <p>Carregando...</p>}
            {isError && <p className="text-danger">Erro ao carregar dados</p>}

            {!isLoading && data && (
                <>
                    <table className="table table-striped">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Partido</th>
                            <th>UF</th>
                        </tr>
                        </thead>
                        <tbody>
                        {Array.isArray(data.data) &&
                            data.data.map((deputado: Deputado) => (
                                <tr
                                    key={deputado.id}
                                    onClick={() => setSelectedId(deputado.id)}
                                    style={{ cursor: 'pointer' }}
                                >
                                    <td>{deputado.nome}</td>
                                    <td>{deputado.sigla_partido}</td>
                                    <td>{deputado.sigla_uf}</td>
                                </tr>
                            ))}
                        </tbody>
                    </table>

                    {/* Paginação - ocultar se só tiver 1 página */}
                    {pagination.last_page > 1 && (
                        <nav>
                            <ul className="pagination">
                                {Array.from({ length: pagination.last_page }, (_, i) => i + 1).map(p => (
                                    <li key={p} className={`page-item ${p === page ? 'active' : ''}`}>
                                        <button onClick={() => setPage(p)} className="page-link">
                                            {p}
                                        </button>
                                    </li>
                                ))}
                            </ul>
                        </nav>
                    )}
                </>
            )}

            {/* Exibe o card com os dados do deputado clicado */}
            {selectedId && <DeputadoCard selectedId={selectedId} />}
        </div>
    );
}
