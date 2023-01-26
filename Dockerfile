FROM php:8.1-rc-apache
RUN curl -L "https://github.com/docker/compose/releases/download/2.15.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose \
 && mkdir /app \
 && mkdir /var/www/tmp \
 && apt update && apt install docker.io -y \
 && chmod +x /usr/local/bin/docker-compose \
 && curl -L "https://raw.githubusercontent.com/mag37/dockcheck/main/dockcheck.sh" -o /app/dockcheck.sh \
 && curl -L "https://github.com/regclient/regclient/releases/download/v0.4.5/regctl-linux-amd64" -o /app/regctl

#RUN apt update && apt install docker.io -y
#RUN chmod +x /usr/local/bin/docker-compose
#RUN curl -L "https://raw.githubusercontent.com/mag37/dockcheck/main/dockcheck.sh" -o /app/dockcheck.sh
#RUN curl -L "https://github.com/regclient/regclient/releases/download/v0.4.5/regctl-linux-amd64" -o /app/regctl

COPY run.sh /app/run.sh
COPY dockcheck /etc/cron.daily/dockcheck
COPY src/ /var/www/tmp

#COPY dockcheck /etc/cron.daily/dockcheck
#COPY src/ /var/www/


#RUN adduser dockcheck
#RUN usermod -aG docker dockcheck
#RUN groupadd docker && gpasswd -a dockcheck docker
#USER dockcheck

ENTRYPOINT ["/app/run.sh"]
