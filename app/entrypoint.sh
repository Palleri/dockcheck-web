#!/bin/sh

echo "# Starting Dockcheck-web #"
echo "# Checking for new updates #"
echo "# This might take a while, it depends on how many containers are running #"



if [ -n "$CRON_TIME" ]; then
    
    hour=$(echo $CRON_TIME | grep -Po "\d*(?=:)")
    minute=$(echo $CRON_TIME | grep -Po "(?<=:)\d*")
    echo -e "\n$minute  $hour   *   *   *   run-parts /etc/periodic/daily" >> /app/root

    else

    echo -e "\n30 12  *   *   *   run-parts /etc/periodic/daily" >> /app/root 
fi

if [ "$NOTIFY" = "true" ]; then
    if [ -n "$NOTIFY_URLS" ]; then
        echo $NOTIFY_URLS > /app/NOTIFY_URLS
        echo "Notify activated"
    fi

    if [ -n "$EXCLUDE" ]; then
    echo $EXCLUDE > /app/EXCLUDE
    fi

    if [ "$NOTIFY_DEBUG" = "true" ]; then
        echo $NOTIFY_DEBUG > /app/NOTIFY_DEBUG
        echo "NOTIFY DEBUGMODE ACTIVATED"  
    fi
fi


sqlite3 /app/containers.db "CREATE TABLE CONTAINERS(
   HOST           TEXT,
   NAME           TEXT,
   LATEST         TEXT,
   ERROR          TEXT,
   NEW            TEXT
);"
sqlite3 /app/containers.db "CREATE TABLE STATE(
   RUNNING        TEXT  NOT NULL
);"


chmod +x /app/postgres
/app/postgres



touch /var/www/update.txt
rm -rf /etc/crontabs/root
cp /app/root /etc/crontabs/root
cp /app/php.ini /etc/php7/php.ini
cd /app && tar xzvf /app/docker.tgz > /dev/null 2>&1 && cp /app/docker/* /usr/bin/ > /dev/null 2>&1
mkdir -p /run/lighttpd/
chown www-data. /run/lighttpd/
cp /app/src/index.php /var/www/index.php
cp /app/src/style.css /var/www/style.css
chmod +x /app/dockcheck*
cp /app/dockcheck /etc/periodic/daily
run-parts /etc/periodic/daily/
chmod +x /app/watcher.sh
/app/watcher.sh </dev/null >/dev/null 2>&1 &
chown -R www-data:www-data /var/www/*
php-fpm7 -D && lighttpd -D -f /etc/lighttpd/lighttpd.conf 