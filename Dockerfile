FROM php:7.4-cli-bullseye

# PHP 7.4 images ship Debian Bullseye, whose security mirrors 404 now.
RUN printf 'deb http://archive.debian.org/debian bullseye main\n\
deb http://archive.debian.org/debian-security bullseye-security main\n' \
        > /etc/apt/sources.list \
    && printf 'Acquire::Check-Valid-Until "false";\nAcquire::AllowInsecureRepositories "true";\n' \
        > /etc/apt/apt.conf.d/99archive \
    && apt-get update && apt-get install -y --no-install-recommends --allow-unauthenticated \
        git \
        unzip \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libzip-dev \
        libonig-dev \
        libxml2-dev \
        default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql mbstring zip gd xml bcmath pcntl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:1 /usr/bin/composer /usr/bin/composer

RUN printf "upload_max_filesize=100M\npost_max_size=100M\nmemory_limit=256M\n" \
        > /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www
ENV COMPOSER_ALLOW_SUPERUSER=1

# Copy project files
COPY . /var/www

# Copy and setup entrypoint script
COPY docker-entrypoint.sh /var/www/docker-entrypoint.sh
RUN chmod +x /var/www/docker-entrypoint.sh

EXPOSE 8000
ENTRYPOINT ["/var/www/docker-entrypoint.sh"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
