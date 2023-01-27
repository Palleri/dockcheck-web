#!/bin/bash
chmod +x /app/dockcheck.sh
cp /var/www/tmp/* /var/www/html/

rm -rf /etc/cron.daily/*
mv /tmp/dockcheck /etc/cron.daily/dockcheck
chmod +x /app/regctl
chmod +x /etc/cron.daily/dockcheck
mv /app/regctl /usr/bin/
/app/dockcheck.sh | sed -r "s:\x1B\[[0-9;]*[mK]::g" > /app/containers
apache2-foreground