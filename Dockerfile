FROM alpine:3.17

RUN adduser -D -u 1000 appuser

RUN apk add \
        --no-cache \
        --update \
        git \
        apache2 \
        composer \
        nano \
        bash \
        curl \
        php \
        php-apache2 \
        php-curl \
        php-dom \
        php-mbstring \
        php-pdo_mysql \
        php-session \
        php-tokenizer \
        php-xml \
        php-xmlwriter \
        php-fileinfo \
        php-ctype \
        php-simplexml \
        php-xmlreader \
        php-zip \
    && mkdir -p /run/apache2 \
    && ln -sf /dev/stdout /var/log/apache2/access.log \
    && ln -sf /dev/stderr /var/log/apache2/error.log

WORKDIR /app

COPY ./apache2.conf /etc/apache2/conf.d/apache2.conf
COPY ./web.sh /run/web.sh
RUN chmod +x /run/web.sh \
&& sed -i 's@^#LoadModule rewrite_module modules/mod_rewrite\.so@LoadModule rewrite_module modules/mod_rewrite.so@' /etc/apache2/httpd.conf \
&& sed -i 's@^User apache@User appuser@' /etc/apache2/httpd.conf \
&& sed -i 's@^Group apache@Group appuser@' /etc/apache2/httpd.conf

EXPOSE 80
