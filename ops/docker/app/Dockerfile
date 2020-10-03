FROM alpine:3.11 as base
ARG FILE_OWNER=nobody
RUN echo "File owner: $FILE_OWNER"

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
    php7-pdo_pgsql \
    php7-mysqli \
    php7-xml \
    php7-zlib \
    php7-phar \
    php7-tokenizer \
    php7-session \
    php7-simplexml \
    php7-xmlwriter \
    php7-zip \
    nginx \
    supervisor \
    curl

# Configure nginx
COPY ops/docker/app/config/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY ops/docker/app/config/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY ops/docker/app/config/php.ini /etc/php7/conf.d/zzz_php.ini

# Configure supervisord
COPY ops/docker/app/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create cache directories
RUN mkdir -p /.composer \
  && mkdir -p /.cache/yarn \
  && mkdir -p /.yarn

# Make sure files/folders needed by the processes are accessable when they run under the $FILE_OWNER user
RUN chown -R $FILE_OWNER.$FILE_OWNER /run && \
  chown -R $FILE_OWNER.$FILE_OWNER /var/lib && \
  chown -R $FILE_OWNER.$FILE_OWNER /var/tmp && \
  chown -R $FILE_OWNER.$FILE_OWNER /var/log && \
  chown -R $FILE_OWNER.$FILE_OWNER /.composer && \
  chown -R $FILE_OWNER.$FILE_OWNER /.cache/yarn && \
  chown -R $FILE_OWNER.$FILE_OWNER /.yarn

# Setup document root
RUN mkdir -p /var/www/html

# Set the workign dir to the document root
WORKDIR /var/www/html

# Set $FILE_OWNER the owner of the work dir
RUN chown $FILE_OWNER /var/www/html

# Switch to use a non-root user from here on
USER $FILE_OWNER

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/





FROM base as dev
ARG FILE_OWNER=nobody

ENV APP_ENV=dev

USER root

COPY ops/docker/app/config/php_xdebug.ini /etc/php7/conf.d/zzz_php_xdebug.ini

RUN apk add --no-cache \
  composer \
  nodejs \
  yarn \
  php7-xdebug

RUN addgroup -S appgroup && adduser -S appuser -G appgroup
USER $FILE_OWNER

ENV PHP_IDE_CONFIG="serverName=portfolio.local"
ENV XDEBUG_CONFIG="idekey=PHPSTORM"

# Disable essentially
HEALTHCHECK --timeout=10s CMD true





FROM base as build
ARG FILE_OWNER=nobody

# We want the artifacts to be built using production settings.
ENV APP_ENV=prod

USER root

# For coverage. This will not be included in the prod stage.
COPY ops/docker/app/config/php_xdebug.ini /etc/php7/conf.d/zzz_php_xdebug.ini

RUN apk add --no-cache \
  composer \
  nodejs \
  yarn \
  php7-xdebug

USER $FILE_OWNER

# Add source code
COPY --chown=$FILE_OWNER . .

RUN composer install --no-interaction --no-ansi

RUN yarn install

RUN yarn encore production

RUN php ./bin/phpunit

RUN sed -i 's+/var/www/html/+./+g' var/coverage/clover.xml \
  && sed -i 's+/var/www/html/+./+g' var/coverage/junit-logfile.xml





FROM base as prod
ARG FILE_OWNER=nobody

ENV APP_ENV=prod

# Copy built application
COPY --chown=$FILE_OWNER --from=build /var/www/html/ /var/www/html/
