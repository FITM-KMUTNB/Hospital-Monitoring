FROM php:7.2.0-fpm-alpine
RUN apk add --update --no-cache libcurl curl libxml2-dev
RUN docker-php-ext-install pdo_mysql bcmath zip opcache
ENV TZ=Asia/Bangkok
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN rm -rf /var/cache/apk/*
WORKDIR /var/app
EXPOSE 9000
CMD ["php-fpm"]