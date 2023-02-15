# dockcheck-web

A webpage showing available image updates for your running containers.

Checking for new images at startup, once a day or via the button "Check for updates".

## Based on [mag37/dockcheck](https://github.com/mag37/dockcheck)

>### A script checking updates for docker images without the need of pulling - then having the option to auto-update.

All cred goes to Mag37 making this amazing script!

This image use [dockcheck](https://github.com/mag37/dockcheck) provided by Mag37.


## Dependencies:
[regclient/regctl](https://github.com/regclient/regclient) (Licensed under Apache-2.0 License)

----

![](https://github.com/Palleri/dockcheck-web/blob/main/examplegui.gif)


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

### Future ideas
* Email notification on available images
* Update and pull new image on selected container via webgui

### Bugs and fixes

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
