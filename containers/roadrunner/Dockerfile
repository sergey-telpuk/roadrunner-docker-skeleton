FROM golang:1.11 as builder

ENV GO111MODULE on
ENV RR_VERSION v1.3.0

RUN apt-get update && apt-get install -y --no-install-recommends git

# Build RoadRunner
WORKDIR /go/src
RUN git clone --depth 1 --branch ${RR_VERSION} https://github.com/spiral/roadrunner \
  && cd /go/src/roadrunner \
  && make \
  && make install

# Composer Docker Container
FROM prooph/composer:7.3-zts as composer
COPY ./src/composer.json /app/composer.json
RUN composer install

# PHP Image
FROM php:7.3-cli

RUN apt-get update && apt-get install -y --no-install-recommends \
  vim \
  libzip-dev \
  unzip

# Copy RoadRunner binary and config
COPY --from=builder /go/src/roadrunner/rr /usr/local/bin/rr
COPY --from=composer /app /var/www/app/vendor

COPY ./containers/roadrunner/config /etc/roadrunner

# Install PHP Extensions
RUN docker-php-ext-install zip opcache

RUN docker-php-ext-enable opcache

WORKDIR /var/www

ENTRYPOINT ["/usr/local/bin/rr", "serve", "-d", "-c", "/etc/roadrunner/.rr.yaml"]
