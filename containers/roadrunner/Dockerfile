FROM golang:latest as builder
ARG GO111MODULE

RUN apt-get update && apt-get install -y --no-install-recommends git

# Build RoadRunner
WORKDIR /go/src
RUN git clone https://github.com/spiral/roadrunner \
  && cd /go/src/roadrunner \
  && make \
  && make install

# PHP Image
FROM php:7.3-zts

RUN apt-get update && apt-get install -y --no-install-recommends \
  vim \
  libzip-dev \
  unzip

# Copy RoadRunner binary and config
COPY --from=builder /go/src/roadrunner/rr /usr/local/bin/rr
COPY config /etc/roadrunner

# Install PHP Extensions
RUN docker-php-ext-install zip opcache pdo pdo_mysql

RUN docker-php-ext-enable opcache

WORKDIR /var/www/app

ENTRYPOINT ["/usr/local/bin/rr", "serve", "-d", "-c", "/etc/roadrunner/.rr.yaml"]
