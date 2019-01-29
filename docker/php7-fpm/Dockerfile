FROM php:7.2.0-fpm-alpine

# RUN apt-get update && apt-get install -y software-properties-common language-pack-en-base \
#     && LC_ALL=en_US.UTF-8 add-apt-repository -y ppa:ondrej/php \
#     && apt-get update \
#     && apt-get install -y php7.0 php7.0-fpm php7.0-mysql mcrypt php7.0-gd curl \
#        php7.0-curl php-redis php7.0-mbstring sendmail supervisor \
#     && mkdir /run/php \
#     && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# RUN apk upgrade --update && apk --no-cache add autoconf tzdata openntpd file g++ gcc binutils isl libatomic libc-dev musl-dev make re2c libstdc++ libgcc libcurl curl-dev binutils-libs mpc1 mpfr3 gmp libgomp coreutils freetype-dev libjpeg-turbo-dev libltdl libmcrypt-dev libpng-dev openssl-dev libxml2-dev expat-dev

RUN apk add --update --no-cache libcurl curl libxml2-dev
RUN docker-php-ext-install pdo_mysql bcmath zip opcache

# TimeZone
ENV TZ=Asia/Bangkok
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# RUN sed -i -e 's/listen = \/run\/php\/php7.0-fpm.sock/listen = 0.0.0.0:9000/g' /etc/php/7.0/fpm/pool.d/www.conf && sed -i -e 's/;daemonize = yes/daemonize = no/g' /etc/php/7.0/fpm/php-fpm.conf

# Install Composer && Assets Plugin
# ENV COMPOSER_ALLOW_SUPERUSER=1
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN composer --version

RUN rm -rf /var/cache/apk/*

WORKDIR /var/app

EXPOSE 9000

# CMD ["/usr/bin/supervisord"]
CMD ["php-fpm"]