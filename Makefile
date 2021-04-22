docker-image:
	docker build \
		-t php_profiler_lite \
		--build-arg UID=`id -u` \
		.
pull:
	docker pull bearstech/php:7.3

up:
	docker-compose up -d
