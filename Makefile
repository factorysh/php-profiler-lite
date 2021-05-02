docker-image:
	docker build \
		-t php_profiler_lite \
		--build-arg UID=`id -u` \
		.

pull:
	docker pull bearstech/php:7.3

docker-mockup:
	docker build \
		-t php_profiler_lite_mockup \
		--build-arg UID=`id -u` \
		-f Dockerfile.mockup \
		.

demo: | docker-mockup docker-image up
	docker-compose logs -f


up:
	docker-compose up -d
