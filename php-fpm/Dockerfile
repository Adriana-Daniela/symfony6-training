FROM php:8.1-fpm
WORKDIR "/app"

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        git \ 
        sqlite3 sqlite3-tools \
        libicu-dev \
        libxslt1-dev \
        curl \
        npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN docker-php-ext-install intl xsl pdo_mysql

RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony5/bin/symfony /usr/local/bin/symfony