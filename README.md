# dockcheck-web

A webpage showing available image updates for your running containers.

Checking for new images at startup, once a day or via the button "Check for updates".

## Based on [mag37/dockcheck](https://github.com/mag37/dockcheck)

>### A script checking updates for docker images without the need of pulling - then having the option to auto-update.

All cred goes to Mag37 making this amazing script!

This image use [dockcheck](https://github.com/mag37/dockcheck) provided by Mag37.


## Dependencies:
[regclient/regctl](https://github.com/regclient/regclient) (Licensed under Apache-2.0 License)

[inotify-tools](https://github.com/inotify-tools/inotify-tools) (Licensed under GPL-2.0 License)

[APPRISE](https://github.com/caronc/apprise) (Licensed under BSD 3-Clause License)

----

![](https://github.com/Palleri/dockcheck-web/blob/main/examplegui.gif)


# Features
* Notifications (Not tested on ARM) via [APPRISE](https://github.com/caronc/apprise)

### Environment variables
Set NOTIFY=true in docker-compose.yml to enable notifications

```yml
version: '3.2'
services:
  ...
    environment:
      - NOTIFY=true
      - DISCORD_NOTIFY=discord://webhook_id/webhook_token
  ...
```

### If you want a different notification service just ask and I will try to implement it (If [APPRISE](https://github.com/caronc/apprise#productivity-based-notifications) have support for it)

| Notification Service | Environment variable | Service ID | Default Port | Example Syntax |
| --- | --- | --- | --- | --- |
| `Discord` | DISCORD_NOTIFY | discord:// | (TCP) 443 | discord://webhook_id/webhook_token <br /> discord://avatar@webhook_id/webhook_token |
| `Telegram` | TELEGRAM_NOTIFY | tgram:// | (TCP) 443 | tgram://bottoken/ChatID <br /> tgram://bottoken/ChatID1/ChatID2/ChatIDN |



### Not Tested
Notification Service | Environment variable | Service ID | Default Port | Example Syntax |
| --- | --- | --- | --- | --- |
| `Mail` | MAIL_NOTIFY | mailto:// | (TCP) 25 | mailto://userid:pass@domain.com <br /> mailto://domain.com?user=userid&pass=password <br /> mailto://domain.com:2525?user=userid&pass=password <br /> mailto://user@gmail.com&pass=password <br /> mailto://mySendingUsername:mySendingPassword@example.com?to=receivingAddress@example.com <br /> mailto://userid:password@example.com?smtp=mail.example.com&from=noreply@example.com&name=no%20reply |

### If cron is not running at the correct time 12:25 make sure this is applied

```yml
version: '3.2'
services:
  ...
      - /etc/localtime:/etc/localtime:ro
  ...
```

docker-compose.yml
```yml
version: '3.2'
services:
  dockcheck-web:
    container_name: dockcheck-web
    image: 'palleri/dockcheck-web:latest'
    restart: unless-stopped
    ports:
      - '80:80'
    volumes:
      - ./data:/var/www/html
      - /var/run/docker.sock:/var/run/docker.sock:ro
      - /etc/localtime:/etc/localtime:ro
    environment:
      - NOTIFY=true
      - DISCORD_NOTIFY=discord://webhook_id/webhook_token
```

# Security concern
For more security add the :ro to volumes docker.sock

Use with care, make sure you keep this container safe and do not published on the internet.


# ARM support 
Available at [hub.docker/Palleri](https://hub.docker.com/r/palleri/dockcheck-web/tags)

Git branch (ARM)

Change image from 
```yml
    image: 'palleri/dockcheck-web:latest'
```
to
```yml
    image: 'palleri/dockcheck-web:arm'
```

# Future ideas
| Feature | Timeline | Implemented |
| --- | --- | --- |
| Notifications (Not tested on ARM) | 2023-02-21 | Alpha |
| Update via webgui | Unknown (Need Help) | |
| Multiple hosts one gui | Unknown | |

* Update via webui
  - Need help with how to make docker.sock recreate docker-compose without the need for docker-compose.yml
    - Docker remote API good or bad?


# Bugs and fixes

| Description | Date | Status |
| --- | --- | --- |
| `Cronjob not working properly` | 2023-01-28 | Closed |
| `Script not running correctly` | 2023-01-29 | Closed |
| `Hanging processes` | 2023-01-29 | Closed |
| `Not displaying in ascending order` | 2023-01-29 | Closed |
| `Blank/error text on index while script is running` | 2023-01-29 | Closed |
| `Redirect error while checking for update` | 2023-01-31 | Closed |

-------


* Contributors
  - [Mag37](https://github.com/Mag37) 👑
  - [t0rnis](https://github.com/t0rnis) 🪖🐛
