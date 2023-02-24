FROM php:8.1-rc-apache
ARG TARGETPLATFORM
ENV NOTIFY="" \
#TARGETPLATFORM=${TARGETPLATFORM} \
#DISCORD_NOTIFY="" \
#TELEGRAM_NOTIFY="" \
#MAIL_NOTIFY="" \
NOTIFY_DEBUG="" \
NOTIFY_URLS=""
RUN case ${TARGETPLATFORM} in \
         "linux/amd64")  os=amd64  ;; \
         "linux/arm64")  os=arm64  ;; \
    esac \
&& mkdir /app \
&& mkdir /var/www/tmp \
&& apt update && apt install cron docker.io inotify-tools pip -y \
&& curl -L "https://raw.githubusercontent.com/mag37/dockcheck/main/dockcheck.sh" -o /app/dockcheck.sh \
&& curl -L "https://github.com/regclient/regclient/releases/download/v0.4.5/regctl-linux-${os}" -o /app/regctl \
#&& curl -L "https://github.com/regclient/regclient/releases/download/v0.4.5/regctl-linux-amd64" -o /app/regctl \
#&& curl -L "https://github.com/regclient/regclient/releases/download/v0.4.5/regctl-linux-arm64" -o /app/regctl \
&& pip install apprise
#COPY run.sh /app/run.sh
#COPY dockcheck /tmp/dockcheck
#COPY crontab /app/crontab
#COPY src/ /var/www/tmp
#COPY watcher.sh /var/www/tmp/watcher.sh
COPY app* /app/
ENTRYPOINT ["/app/run.sh", "env"]