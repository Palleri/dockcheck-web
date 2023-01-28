# DockCheck
## All cred goes to Mag37 making this amazing script!
### This image use dockcheck provided by Mag37.
[Dockcheck @github/Mag37](https://github.com/mag37/dockcheck)
-------

### A script checking updates for docker images without the need of pulling - then having the option to auto-update.

This image provide an webpage running as a container.

<img alt="logo" src="https://i.imgur.com/ElFdJ9t.png">

-------

# ARM support now available at [hub.docker/Palleri](https://hub.docker.com/r/palleri/dockcheck-web/tags)
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

1. <s>Cronjob not working properly</s> <b>(Fixed 2023-01-28)</b>


-------
Checking for new images at startup and once a day at midnight.


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
      - /var/run/docker.sock:/var/run/docker.sock
```
* Contributors
  - [Mag37](https://github.com/Mag37) 👑
  - [t0rnis](https://github.com/t0rnis) 🪖🐛
