PHP proflier lite
=================

Something like [php-profiler](https://github.com/perftools/php-profiler)
but opiniated, and usable inside a container with some ENV.

* PHP >= 7.0 packaged by Debian
* php-curl
* [php-tideways](https://github.com/tideways/php-xhprof-extension)
* target [xhgui](https://github.com/perftools/xhgui) http upload, or filling a folder with plain old files.

Settings
--------

`PHP_PROFILER_TOKEN` : some random stuff

`PHP_PROFILER_FLAGS` : comma separated flags

* CPU
* MEMORY
* NO_BUILTINS
* NO_SPANS

`PHP_PROFILER_URL` : where to send the profile

* file
* http/https

Demo
----

    make docker-image
    make up
    curl -H "php-profiler-token: toto"  -v http://localhost:8000/
