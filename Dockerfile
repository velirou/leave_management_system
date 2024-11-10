FROM php:8.0-apache

# Install system dependencies and libraries for PHP extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    default-libmysqlclient-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy application files
COPY ./public /var/www/html
COPY ./src /var/www/html/src

# Expose port 80
EXPOSE 80
