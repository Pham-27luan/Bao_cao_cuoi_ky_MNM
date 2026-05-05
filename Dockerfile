FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libzip-dev \
        libicu-dev \
        libonig-dev \
        libpq-dev \
    && docker-php-ext-install \
        bcmath \
        intl \
        mbstring \
        pdo_mysql \
        pdo_pgsql \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . /var/www

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

COPY docker/start.sh /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
