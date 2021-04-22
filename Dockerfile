FROM bearstech/php:7.3

RUN set -eux \
    &&  apt-get update \
    &&  apt-get install -y --no-install-recommends \
        php-tideways \
    &&  apt-get clean \
    &&  rm -rf /var/lib/apt/lists/*

COPY profiler_lite.php /opt/php_profiler_lite/
COPY 02-php_profiler_lite.sh /entrypoint.d/


ARG UID=1001
RUN useradd alice --uid ${UID} --shell /bin/bash
COPY www /var/www/web
USER alice
