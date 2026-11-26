FROM php:8.6-cli-alpine3.23@sha256:46960078e968d1af09be1680a823272529c1bf578cb5ed0b3126aa7dfc296162

RUN apk add --no-cache bash

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN install-php-extensions @composer xdebug
