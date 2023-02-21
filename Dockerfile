FROM php:8.1-rc-apache
ENV NOTIFY=""
ENV DISCORD_NOTIFY=""
ENV TELEGRAM_NOTIFY=""
ENV MAIL_NOTIFY=""
RUN curl -L "https://github.com/docker/compose/releases/download/v2.15.1/docker-compose-linux-x86_64" -o /usr/local/bin/docker-compose \
 && mkdir /app \
 && mkdir /var/www/tmp \
 && apt update && apt install cron docker.io inotify-tools pip -y \
 && chmod +x /usr/local/bin/docker-compose \
 && curl -L "https://raw.githubusercontent.com/mag37/dockcheck/main/dockcheck.sh" -o /app/dockcheck.sh \
 && curl -L "https://github.com/regclient/regclient/releases/download/v0.4.5/regctl-linux-amd64" -o /app/regctl \
 && pip install apprise

COPY run.sh /app/run.sh
COPY dockcheck /tmp/dockcheck
COPY crontab /app/crontab
COPY src/ /var/www/tmp
COPY watcher.sh /var/www/tmp/watcher.sh

ENTRYPOINT ["/app/run.sh", "env", "env", "env"]