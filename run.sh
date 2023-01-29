#!/bin/bash
echo "# Starting Dockcheck-web #"
echo "# Checking for new updates #"
echo "# This might take a while, it depends on how many containers are running #"
chmod +x /app/dockcheck.sh
cp /var/www/tmp/* /var/www/html/

rm -rf /etc/cron.daily/*
cp /tmp/dockcheck /etc/cron.daily/dockcheck
chmod +x /app/regctl
chmod +x /etc/cron.daily/dockcheck
cp /app/regctl /usr/bin/
/app/dockcheck.sh -n | sed -r "s:\x1B\[[0-9;]*[mK]::g" > /app/containers_temp
cat /app/containers_temp > /app/containers
service cron start
apache2-foreground