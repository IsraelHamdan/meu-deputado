FROM php:8.2-fpm-bullseye

# Instala extensões necessárias
RUN apt-get update && apt-get upgrade -y && apt-get install -y \
  build-essential \
  libpng-dev \
  libjpeg62-turbo-dev \
  libfreetype6-dev \
  libonig-dev \
  libxml2-dev \
  zip \
  unzip \
  curl \
  git \
  nano \
  libpq-dev \
  && docker-php-ext-install pdo pdo_pgsql \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia arquivos da aplicação
WORKDIR /var/www
COPY . .

# Instala dependências do Laravel
RUN composer install

# Permissões
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

EXPOSE 8000
