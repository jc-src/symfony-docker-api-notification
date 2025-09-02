#!/bin/sh

# Build params based on Variables
if [ -f "/var/www/composer.lock" ]; then
    export COMPOSER_ALLOW_SUPERUSER=1
    php bin/console cache:warmup
    export COMPOSER_ALLOW_SUPERUSER=0
fi

#php-fpm --allow-to-run-as-root

/usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf --nodaemon
