FROM alpine:3.11 as base

RUN apk --no-cache add \
    php7-fpm \
    php7-apcu \
    php7-ctype \
    php7-curl \
    php7-dom \
    php7-gd \
    php7-iconv \
    php7-imagick \
    php7-json \
    php7-intl \
    php7-mcrypt \
    php7-fileinfo \
    php7-mbstring \
    php7-opcache \
    php7-openssl \
    php7-pdo \
    php7-pdo_mysql \
    php7-mysqli \
    php7-xml \
    php7-zlib \
    php7-phar \
    php7-tokenizer \
    php7-session \
    php7-simplexml \
    php7-xmlwriter \
    php7-xdebug \
    php7-zip \
    nginx \
    supervisor \
    curl

# Configure nginx
COPY ops/docker/app/config/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY ops/docker/app/config/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY ops/docker/app/config/php.ini /etc/php7/conf.d/zzz_custom.ini

# Configure supervisord
COPY ops/docker/app/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /run && \
  chown -R nobody.nobody /var/lib && \
  chown -R nobody.nobody /var/tmp && \
  chown -R nobody.nobody /var/log

# Setup document root
RUN mkdir -p /var/www/html

# Switch to use a non-root user from here on
USER nobody

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/





FROM base as dev

ENV APP_ENV=dev





FROM base as prod

ENV APP_ENV=prod

# Add application
WORKDIR /var/www/html
COPY --chown=nobody ./ /var/www/html/
