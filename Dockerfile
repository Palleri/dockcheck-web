FROM php:8.1-rc-apache



RUN curl -L "https://github.com/docker/compose/releases/download/2.15.1/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose


RUN apt update && apt install docker.io -y
RUN chmod +x /usr/local/bin/docker-compose


RUN mkdir /app
COPY run.sh /app/run.sh 

#COPY bin/* /etc/
COPY src /var/www/


#RUN adduser dockcheck
#RUN usermod -aG docker dockcheck
#RUN groupadd docker && gpasswd -a dockcheck docker
#USER dockcheck

ENTRYPOINT ["/app/run.sh"]
