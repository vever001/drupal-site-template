FROM  fpfis/httpd-php-oci-dev:7.3

# Install extra packages.
RUN apt-get update \
  && apt-get install -y iproute2 php-mongodb php-tideways

# Create an "fpfis" user with given UID and GID (to use for bash and FPM).
# The UID and GID should match the ones from your host, running phpstorm.
ARG FPFIS_USER_ID=1000
ARG FPFIS_GROUP_ID=1000
RUN set -xe; \
	\
	# Delete existing user/group if uid/gid occupied.
	existing_group=$(getent group "${FPFIS_GROUP_ID}" | cut -d: -f1); \
	if [ -n "${existing_group}" ]; then delgroup "${existing_group}"; fi; \
	existing_user=$(getent passwd "${FPFIS_USER_ID}" | cut -d: -f1); \
	if [ -n "${existing_user}" ]; then deluser "${existing_user}"; fi; \
	\
	addgroup --gid "${FPFIS_GROUP_ID}" fpfis; \
	adduser --uid "${FPFIS_USER_ID}" --system --shell /bin/bash --group fpfis; \
	adduser fpfis www-data; \
	sed -i '/^fpfis/s/!/*/' /etc/shadow;

# Comment "request_terminate_timeout" in PHP FPM config.
# This timeout prevents Xdebugging effectively...
RUN sed -i 's/^request_terminate_timeout/;&/' /etc/php/7.3/fpm/pool.d/www.conf

# Custom PHP config.
ADD 99-custom.ini /etc/php/7.3/fpm/conf.d/
ADD 99-custom.ini /etc/php/7.3/cli/conf.d/

# Add vendor/bin to PATH (avoids us to type ./vendor/bin/... every time).
ENV PATH="${PATH}:/var/www/html/vendor/bin"

# Override the entrypoint.
ADD entrypoint.sh /scripts/
RUN chmod +x /scripts/entrypoint.sh
ENTRYPOINT ["/scripts/entrypoint.sh"]
