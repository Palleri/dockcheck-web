FROM alpine:3.15

RUN apk --update add --no-cache \
    lighttpd \
    bash \
    curl \
    vim \
    py-pip \
    inotify-tools \
    sqlite \
    grep \
    php-common \
    php-fpm \
    php-pgsql \
    #php-sqlite3 \
    #php-iconv \
    #php-json \
    #php-gd \
    php-curl \
    #php-xml \
    #php-simplexml \
    #php-pgsql \
    #php-imap \
    php-cgi \
    fcgi \
    php-pdo \
    #php-pdo_sqlite \
    php-pdo_pgsql \
    #php-soap \
    #php-xmlrpc \
    #php-posix \
    #php-gettext \
    #php-ldap \
    #php-ctype \
    #php-dom && \
    postgresql \
    && rm -rf /var/cache/apk/* 
    

COPY app* /app/ 
ADD lighttpd.conf /etc/lighttpd/lighttpd.conf 
RUN adduser www-data -G www-data -H -s /bin/bash -D \
&& curl -L "https://raw.githubusercontent.com/mag37/dockcheck/main/dc_brief.sh" -o /app/dockcheck.sh \
&& curl -L "https://download.docker.com/linux/static/stable/x86_64/docker-23.0.1.tgz" -o /app/docker.tgz \
&& curl -L "https://github.com/regclient/regclient/releases/download/v0.4.5/regctl-linux-amd64" -o /app/regctl \
&& pip install apprise
EXPOSE 80
#VOLUME /var/www


ENTRYPOINT [ "/app/entrypoint.sh" ]
