#!/bin/bash
while inotifywait -e modify /var/www/update.txt; do
        run-parts /etc/periodic/daily/
        echo 0 > /var/www/update.txt
done
root