.PHONY: nmap-webapp docker-pull

build: nmap-webapp

docker-pull:
	docker pull php:8.1-apache

nmap-webapp: docker-pull
	docker build --no-cache -t nmap-webapp -f Dockerfile .