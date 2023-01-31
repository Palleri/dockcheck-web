#!/bin/sh
while inotifywait -e modify update.txt; do
        run-parts /etc/cron.daily/
        echo 0 > update.txt
done
root