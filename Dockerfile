FROM php:8.1-rc-apache
RUN curl -L "https://github.com/docker/compose/releases/download/v2.15.1/docker-compose-linux-armv7" -o /usr/local/bin/docker-compose
RUN mkdir /app
RUN mkdir /var/www/tmp
RUN apt update && apt install cron -y && apt install docker.io -y && apt install inotify-tools -y \
RUN chmod +x /usr/local/bin/docker-compose
RUN curl -L "https://raw.githubusercontent.com/mag37/dockcheck/main/dockcheck.sh" -o /app/dockcheck.sh
RUN curl -L "https://github.com/regclient/regclient/releases/download/v0.4.5/regctl-linux-arm64" -o /app/regctl

COPY run.sh /app/run.sh
COPY dockcheck /tmp/dockcheck
COPY src/ /var/www/tmp

ENTRYPOINT ["sh", "/app/run.sh"]