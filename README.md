# 🚀 Guia de Execução da Aplicação

Este guia irá te ajudar a executar a aplicação tanto usando **Docker** quanto executando **localmente**.

## 📋 Pré-requisitos

- Docker e Docker Compose instalados (para execução com containers)
- PHP 8.2+ e Node.js 20.17+ (para execução local)

---

## 🐳 Execução com Docker

### 1. Construindo os Containers

Você pode buildar os containers de duas maneiras:

#### Opção A: Buildar todos os containers de uma vez
```bash
docker-compose build
```

#### Opção B: Buildar containers individualmente
```bash
# Container do banco de dados
docker-compose build db

# Container do backend (Laravel)
docker-compose build backend

# Container do frontend (Next.js)
docker-compose build frontend
```

### 2. Iniciando os Containers

Após buildar os containers, execute o comando para iniciá-los:

```bash
docker-compose up -d
```

### 3. Visualizando os Logs

Se você quiser acompanhar os logs dos containers, utilize os seguintes comandos:

```bash
# Logs do servidor Laravel
docker logs -f laravel-app

# Logs do banco de dados
docker logs -f db

# Logs do cliente Next.js
docker logs -f next-app
```

---

## 💻 Execução Local

### 1. Pré-requisitos para Execução Local

Certifique-se de ter instalado:

- **PHP versão 8.2 ou superior**
- **Node.js versão 20.17 ou superior**

### 2. Configuração do Banco de Dados

> ⚠️ **IMPORTANTE**: O banco de dados deve ser **PostgreSQL**

1. Navegue até a pasta `api` do projeto
2. Localize o arquivo `.env.example`
3. Crie um arquivo `.env` baseado no exemplo
4. Configure as seguintes variáveis de conexão com o PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=nome_do_seu_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

> 📝 **Dica**: É importante seguir exatamente a nomenclatura das variáveis mostradas acima.

### 3. Executando a Aplicação

#### Backend (Laravel)
Navegue até a pasta da API e execute:

```bash
php artisan serve
```

#### Frontend (Next.js)
Navegue até a pasta do frontend e execute:

```bash
npm run dev
```

---

## 📚 Comandos Úteis

### Docker
```bash
# Parar todos os containers
docker-compose down

# Reiniciar os containers
docker-compose restart

# Ver status dos containers
docker-compose ps
```

### Local
```bash
# Instalar dependências do Laravel
composer install

# Instalar dependências do Next.js
npm install

# Executar migrações do banco
php artisan migrate
```

---

## 🆘 Problemas Comuns

- **Erro de conexão com banco**: Verifique se o PostgreSQL está rodando e as credenciais estão corretas
- **Porta já em uso**: Verifique se as portas 3000 (frontend) e 8000 (backend) estão disponíveis
- **Containers não iniciam**: Execute `docker-compose logs` para ver os erros detalhados

---

**Pronto! 🎉 Sua aplicação deve estar rodando corretamente.**
