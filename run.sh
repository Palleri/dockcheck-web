#!/bin/sh
echo "# Starting Dockcheck-web #"
echo "# Checking for new updates #"
echo "# This might take a while, it depends on how many containers are running #"
chmod +x /app/dockcheck.sh
chmod +x /var/www/tmp/watcher.sh
cp /var/www/tmp/* /var/www/html/
touch /var/www/html/update.txt
chown www-data:www-data /var/www/html/update.txt

rm -rf /etc/cron.daily/*
cp /tmp/dockcheck /etc/cron.daily/dockcheck
chmod +x /app/regctl
chmod +x /etc/cron.daily/dockcheck
cp /app/regctl /usr/bin/
/app/dockcheck.sh -n | sed -r "s:\x1B\[[0-9;]*[mK]::g" > /app/containers_temp
cat /app/containers_temp > /app/containers
service cron start
/var/www/html/watcher.sh </dev/null >/dev/null 2>&1 & disown
apache2-foreground