#!/bin/bash
curl -L "https://raw.githubusercontent.com/mag37/dockcheck/main/dockcheck.sh" > /app/dockcheck.sh
chmod +x /app/dockcheck.sh
cp /var/www/* /var/www/html/

curl -L "https://github.com/regclient/regclient/releases/download/v0.4.5/regctl-linux-amd64" > /app/regctl
chmod +x /app/regctl
mv /app/regctl /usr/bin/
/app/dockcheck.sh | sed -r "s:\x1B\[[0-9;]*[mK]::g" > /app/containers

apache2-foreground