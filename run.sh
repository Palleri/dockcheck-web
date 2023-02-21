#!/bin/bash
echo "# Starting Dockcheck-web #"
echo "# Checking for new updates #"
echo "# This might take a while, it depends on how many containers are running #"



if [ "$NOTIFY" == "true" ]; then

    if [ ! -z "$DISCORD_NOTIFY" ]; then
        echo $DISCORD_NOTIFY > /app/DISCORD_NOTIFY
    fi

    if [ ! -z "$MAIL_NOTIFY" ]; then
        echo $MAIL_NOTIFY > /app/MAIL_NOTIFY
    fi

    if [ ! -z "$TELEGRAM_NOTIFY" ]; then
        echo $TELEGRAM_NOTIFY > /app/TELEGRAM_NOTIFY
    fi
fi


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