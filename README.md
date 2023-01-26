# DockCheck
## All cred goes to Mag37 making this amazing script!
### This image use dockcheck provided by Mag37.
[Dockcheck @github/Mag37](https://github.com/mag37/dockcheck)
-------

### A script checking updates for docker images without the need of pulling - then having the option to auto-update.

This image provide an webpage running as a container.


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
