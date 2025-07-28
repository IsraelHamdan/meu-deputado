FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
  zip unzip curl git libpq-dev libonig-dev libxml2-dev \
  && docker-php-ext-install pdo pdo_pgsql mbstring xml

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /var/www

# Copia os arquivos da aplicação
COPY . .

# Instala dependências PHP (SÓ NA CONSTRUÇÃO)
RUN composer install --no-dev --optimize-autoloader

# Dá permissões para o storage
RUN chown -R www-data:www-data storage bootstrap/cache

# Exponha a porta
EXPOSE 8000

# Comando padrão (roda sempre que iniciar o container)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
