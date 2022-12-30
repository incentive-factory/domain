FROM php:8.1-fpm

ENV PHP_SECURITY_CHECHER_VERSION=2.0.6

RUN apt-get update && apt-get install -y \
      unzip \
      git \
      fish

RUN apt-get update && apt-get install -y libzip-dev && docker-php-ext-install zip

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

ENV PATH $PATH:/root/.composer/vendor/bin

RUN composer config -g repo.packagist composer https://packagist.org

# Security checker tool
RUN curl -L https://github.com/fabpot/local-php-security-checker/releases/download/v${PHP_SECURITY_CHECHER_VERSION}/local-php-security-checker_${PHP_SECURITY_CHECHER_VERSION}_linux_$(dpkg --print-architecture) --output /usr/local/bin/local-php-security-checker && \
  chmod +x /usr/local/bin/local-php-security-checker

ADD php.ini /usr/local/etc/php/conf.d/

WORKDIR /var/www