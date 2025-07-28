FROM node:20.17.0

WORKDIR /app

COPY . .

# Usa versão específica do NPM
RUN npm install -g npm@10.8.2

# Instala dependências na build
RUN npm install

EXPOSE 3000
