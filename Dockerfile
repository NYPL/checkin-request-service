FROM webdevops/php-nginx:8.3

WORKDIR /var/www/html
ENV WEB_DOCUMENT_ROOT /var/www/html/src

RUN apt-get update \
    && apt-get install -y procps

EXPOSE 80

COPY ./ $WORKDIR