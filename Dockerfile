FROM php:8.2-cli

WORKDIR /app

# Instalar PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pgsql pdo_pgsql

# Copiar archivos
COPY . .

# Ejecutar servidor PHP
CMD php -S 0.0.0.0:8080
