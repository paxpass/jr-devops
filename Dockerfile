FROM php:8.3-fpm-alpine AS build
RUN apk add --no-cache \
    git unzip libzip-dev icu-dev oniguruma-dev \
    && docker-php-ext-install pdo pdo_mysql intl bcmath

WORKDIR /app
COPY . .
RUN php artisan optimize

FROM php:8.3-fpm-alpine
RUN addgroup -g 1000 app && adduser -G app -g app -s /bin/sh -D app
USER app

WORKDIR /app
COPY --from=build /app /app

CMD ["php-fpm"]