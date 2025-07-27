import { useDeputado } from '@/hooks/use-deputados';
import { ApiResponseDeputados } from '@/interfaces/ApiResponse';
import { Deputado } from '@/interfaces/DeputadosResponse';


export default function DeputadoCard(selectedId: number) {
    // @ts-ignore
    const {data, isLoading, isError, isSuccess} = useDeputado<ApiResponseDeputados>(selectedId || 0);

    if(isLoading) return <div> Carregando</div>

    if(isError) return <div> Erro</div>

    if (data) {
        // @ts-ignore
        const deputado: Deputado = data.data;
        return (
            <div className="card my-4 shadow-sm">
                <div className="card-body d-flex align-items-center">
                    <img
                        src={deputado.url_foto}
                        alt={deputado.nome}
                        className="rounded-circle me-4"
                        style={{ width: '100px', height: '100px', objectFit: 'cover' }}
                    />
                    <div>
                        <h5 className="card-title">{deputado.nome}</h5>
                        <p className="card-text"><strong>Email:</strong> {deputado.email}</p>
                        <p className="card-text mb-1"><strong>Partido:</strong> {deputado.sigla_partido}</p>
                        <p className="card-text mb-1"><strong>Partido link:</strong> {deputado.uri_partido}</p>
                        <p className="card-text mb-1"><strong>UF:</strong> {deputado.sigla_uf}</p>
                        <p className="card-text mb-1"><strong>Legislatura:</strong> {deputado.id_legislatura}</p>
                    </div>
                </div>
            </div>
        )
    }
}
