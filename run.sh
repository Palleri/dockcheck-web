#!/bin/bash
curl -L "https://raw.githubusercontent.com/mag37/dockcheck/main/dockcheck.sh" > /app/dockcheck.sh
chmod +x /app/dockcheck.sh
cp /var/www/* /var/www/html/

apache2-foreground