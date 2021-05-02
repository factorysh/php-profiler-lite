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

test: | docker-mockup docker-image up
	curl -v http://localhost:8000?PHP_PROFILER_TOKEN=toto
	curl -v http://localhost:5000/dump
