#!/usr/bin/env bash

# Run the following line every time the machine is brought up so that the host's IP is retrieved even if it changes
# after a restart.
REMOTE_HOST=$(ip -4 route show default | awk '/^default/ { print $3 }')
echo "xdebug.remote_host=${REMOTE_HOST}" >> /etc/php/7.3/fpm/conf.d/99-custom.ini
echo "xdebug.remote_host=${REMOTE_HOST}" >> /etc/php/7.3/cli/conf.d/99-custom.ini

# Run the original entrypoint.
exec /scripts/run.sh "$@"
