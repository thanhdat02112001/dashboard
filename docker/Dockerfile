FROM php:7.4-fpm-alpine
ENV LANG C.UTF-8
ENV TZ=Asia/Tokyo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
RUN echo http://dl-2.alpinelinux.org/alpine/edge/community/ >> /etc/apk/repositories
ARG BUILD_PACKAGES="build-base curl-dev git supervisor libpng libpng-dev libjpeg-turbo-dev libwebp-dev zlib-dev libxpm-dev imagemagick imagemagick-libs imagemagick-dev"
ARG DEV_PACKAGES="bash"
RUN apk add --update \
    $BUILD_PACKAGES \
    $DEV_PACKAGES
RUN docker-php-ext-configure gd --enable-gd --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql gd\
    && docker-php-ext-enable pdo_mysql gd
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer \
    --version=2.0.9
RUN apk --update --no-cache add autoconf g++ make && \
    pecl install -f xdebug imagick && \
    docker-php-ext-enable xdebug imagick && \
    apk del --purge autoconf g++ make
COPY ./docker-entrypoint.sh /
RUN chmod +x /docker-entrypoint.sh
COPY ./wait-for-it.sh /
RUN chmod +x /wait-for-it.sh
WORKDIR /app
