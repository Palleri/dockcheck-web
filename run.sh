#!/bin/bash
curl -L "https://raw.githubusercontent.com/mag37/dockcheck/main/dockcheck.sh" > /app/dockcheck.sh
chmod +x /app/dockcheck.sh
apache2-foreground