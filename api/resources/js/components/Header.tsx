import React from 'react';

const HeaderComponent: React.FC = () => {
    const handleNavigate = (page: string) => {
        router.visit(`/${page}`);
    };
    return (
            <nav className="navbar navbar-expand-lg navbar-dark bg-primary shadow">
                <div className="container-fluid">
                    {/* Nome do App */}
                    <a className="navbar-brand fw-bold fs-4" href="/">
                        Meu Deputado
                    </a>

                    {/* Botão de toggle para mobile */}
                    <button
                        className="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarNav"
                        aria-controls="navbarNav"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                    >
                        <span className="navbar-toggler-icon"></span>
                    </button>

                    {/* Menu de navegação */}
                    <div className="collapse navbar-collapse" id="navbarNav">
                        <ul className="navbar-nav ms-auto">
                            <li className="nav-item">
                                <button
                                    className="btn btn-outline-light me-2"
                                    onClick={() => handleNavigate('deputados')}
                                >
                                    <i className="bi bi-people-fill me-1"></i>
                                    Deputados
                                </button>
                            </li>
                            <li className="nav-item">
                                <button
                                    className="btn btn-outline-light me-2"
                                    onClick={() => handleNavigate('partidos')}
                                >
                                    <i className="bi bi-diagram-3-fill me-1"></i>
                                    Partidos
                                </button>
                            </li>
                            <li className="nav-item">
                                <button
                                    className="btn btn-outline-light"
                                    onClick={() => handleNavigate('despesas')}
                                >
                                    <i className="bi bi-cash-stack me-1"></i>
                                    Despesas
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
    )
}

export default HeaderComponent;
