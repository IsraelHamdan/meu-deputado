FROM node:20 as frontend_builder
WORKDIR /var/www
COPY package*.json ./
RUN npm install

FROM php:8.2-fpm

# Instala dependências do sistema (sem node/npm aqui!)
RUN apt-get update && apt-get install -y \
    bash \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    autoconf \
    build-essential \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    supervisor \
    && docker-php-ext-install pdo pdo_pgsql

WORKDIR /var/www

# Instala extensões PHP
RUN docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copia app e node_modules
COPY . .
COPY --from=frontend_builder /var/www/node_modules ./node_modules

# Instala dependências PHP
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Permissões
RUN chown -R www-data:www-data /var/www

# Copia o arquivo para o local correto
COPY supervisord.conf /etc/supervisor/supervisord.conf

# E usa o CMD correto
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/supervisord.conf"]

