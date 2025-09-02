FROM alpine:3.18.4 as production
LABEL Maintainer="Jacob Christensen <jc@divaid.com>" \
      Description="Nginx 1.20 & PHP 8.1 based on Alpine Linux."

ARG BUILD_TAG
ARG BUILD_COMMIT

ENV WORK_DIR /var/www
ENV APP_ENV 'prod'
ENV APP_DEBUG false
ENV IMAGE_TAG ${BUILD_TAG:-NOT_DEFINED}
ENV IMAGE_COMMIT ${BUILD_COMMIT:-NOT_DEFINED}
# only used in developer stage
ENV XDEBUG_YOUR_COMPUTER_IP '10.0.0.11'

# fpm, nginx and global session folder ( if needed )
ENV clear_env=no \
    upload_max_filesize=10M \
    client_max_body_size=10M \
    php_session_folder="/var/php_sessions"

# Install packages
RUN apk --no-cache add \
        php81 \
        php81-fpm \
        php81-opcache \
        php81-pecl-apcu \
        php81-mysqli \
        php81-pgsql \
        php81-json \
        php81-openssl \
        php81-curl \
        php81-zlib \
        php81-soap \
        php81-xml \
        php81-fileinfo \
        php81-phar \
        php81-intl \
        php81-dom \
        php81-xmlreader \
        php81-ctype \
        php81-session \
        php81-iconv \
        php81-tokenizer \
        php81-zip \
        php81-simplexml \
        php81-mbstring \
        php81-gd \
        php81-xmlwriter \
        php81-pecl-amqp \
        php81-sockets \
        php81-sodium \
        php81-pdo \
        php81-pdo_mysql \
        nginx \
        runit \
        curl \
    && apk add --no-cache --virtual .gettext gettext \
    && mv /usr/bin/envsubst /tmp/ \
    && runDeps="$( \
        scanelf --needed --nobanner /tmp/envsubst \
            | awk '{ gsub(/,/, "\nso:", $2); print "so:" $2 }' \
            | sort -u \
            | xargs -r apk info --installed \
            | sort -u \
    )" \
    && apk add --no-cache $runDeps \
    && apk del .gettext \
    && mv /tmp/envsubst /usr/local/bin/ \
    # Remove alpine cache
    && rm -rf /var/cache/apk/* \
    # Remove default server definition
    && rm /etc/nginx/http.d/default.conf \
    # Make sure files/folders needed by the processes are accessable when they run under the nobody user
    && chown -R nobody.nobody /run \
    && chown -R nobody.nobody /var/lib/nginx \
    && chown -R nobody.nobody /var/log/nginx

RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer && \
    alias composer='php /usr/bin/composer'

# Add configuration files
COPY --chown=nobody docker/nginx-fpm/rootfs/ /

RUN mkdir -p ${WORK_DIR}/var && \
    mkdir -p ${WORK_DIR}/var/log && \
    mkdir -p ${WORK_DIR}/var/cache && \
    chown -R nobody.nobody ${WORK_DIR}/var && \
    chown -R nobody.nobody ${WORK_DIR}/var/log && \
    chown -R nobody.nobody ${WORK_DIR}/var/cache

# Add application
WORKDIR ${WORK_DIR}

# Switch to use a non-root user from here on
USER nobody

# Copy project files
COPY --chown=nobody application/ ${WORK_DIR}


RUN echo "{\"commmit\": \"${BUILD_COMMIT:-NOT_DEFINED}\", \"tag\": \"${BUILD_TAG:-NOT_DEFINED}\"}" > ${WORK_DIR}/public/version.json

RUN composer install --no-ansi --no-progress --optimize-autoloader

# Expose the port nginx is reachable on
EXPOSE 8080

# Let runit start nginx & php-fpm
CMD [ "/bin/docker-entrypoint.sh" ]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping

FROM production as developer

USER root
RUN apk --no-cache add \
    php81-pecl-xdebug

RUN echo 'zend_extension=xdebug.so' >> /etc/php81/conf.d/50_xdebug.ini && \
    echo 'xdebug.mode=debug' >> /etc/php81/conf.d/50_xdebug.ini && \
    echo 'xdebug.idekey=docker' >> /etc/php81/conf.d/50_xdebug.ini && \
    echo 'xdebug.start_with_request=yes' >> /etc/php81/conf.d/50_xdebug.ini && \
    echo 'xdebug.log=/dev/stdout' >> /etc/php81/conf.d/50_xdebug.ini && \
    echo 'xdebug.log_level=0' >> /etc/php81/conf.d/50_xdebug.ini && \
    echo 'xdebug.client_port=9003' >> /etc/php81/conf.d/50_xdebug.ini && \
    echo 'xdebug.client_host=${XDEBUG_YOUR_COMPUTER_IP}' >> /etc/php81/conf.d/50_xdebug.ini
USER nobody
