#!/bin/bash

if [ -n "${PHP_PROFILER_TOKEN}" ]; then
	conf="
php_value[auto_prepend_file] = /opt/php_profiler_lite/profiler_lite.php
"
	echo "$conf" >> "/etc/php/${PHP_VERSION}/fpm/pool.d/www.conf"
	echo "PHP Profiler"
fi
