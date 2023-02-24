# dockcheck-web with ARM support

A webpage showing available image updates for your running containers.

Checking for new images at startup, once a day and via the button "Check for updates".

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
      NOTIFY: "true"
      NOTIFY_URLS: "discord://Dockcheck-web@xxxxx/xxxxxx"
```

# Security concern
For more security add the :ro to volumes docker.sock

Use with care, make sure you keep this container safe and do not publish on the internet.

# Proxy
### If you want to use proxy you can use HTTP_PROXY environment variable

```yml
version: '3.2'
services:
  ...
    environment:  
      HTTP_PROXY: "http://proxy.homelab.net:3128"
      HTTPS_PROXY: "http://proxy.homelab.net:3128"
  ...
```


# Date & Timezone
### If cron is not running at the correct time 17:30 make sure this is applied

```yml
version: '3.2'
services:
  ...
    volumes:  
      - /etc/localtime:/etc/localtime:ro
  ...
```
Might also need timezone

```yml
version: '3.2'
services:
  ...
    volumes:  
      - /etc/localtime:/etc/localtime:ro
      - /etc/timezone:/etc/timezone:ro
  ...
```

# Notifications
This image use [apprise](https://github.com/caronc/apprise) for notifications

### Environment variables
Set `NOTIFY: true` to enable notifications

Set `NOTIFY: true` to enable DEBUG mode. Be carefull, your tokens and passwords might be visible in docker logs

Set `NOTIFY_URLS: "tgram://0123456789:RandomLettersAndNumbers-2morestuff-123456789"`

### Setup Example for Telegram <img src="https://github.com/walkxcode/dashboard-icons/blob/main/png/telegram.png" width="26"> 
Start a chat with `@BotFather` and follow the guided steps.   
Create a new bot: `/newbot`   
Nickname: `PumpkinsInShorts` Username: `CuriousPlumber`   
Note down the token replied: *Use this token to access the HTTP API:*   
`0123456789:RandomLettersAndNumbers-2morestuff`   

Now create a new group in Telegram, invite the bot with it's username `@CuriousPlumber`   
When in the group, note down the end of the url, eg `web.telegram.org/z/#-123456789` -> `-123456789`   
If only using the phone app, invite `@RawDataBot` to the group and it'll post the id.   

Then finally combine the token + the ID of the chatgroup, in this example:   
`0123456789:RandomLettersAndNumbers-2morestuff-123456789`   
Add that row to the docker-compose.yml   
```yml
version: '3.2'
services:
  ...
    environment:
      NOTIFY: "true"
      NOTIFY_URLS: "tgram://0123456789:RandomLettersAndNumbers-2morestuff-123456789"
  ...
```

### Setup Example for Discord <img src="https://github.com/walkxcode/dashboard-icons/blob/main/png/discord.png" width="26"> 

Remove the `api/webhook` from `https://discord.com/api/webhooks/xxxxx/xxxxxx`   
To this `discord://xxxxx/xxxxxx`   

Or use it as it is `https://discord.com/api/webhooks/xxxxx/xxxxxx`   

```yml
version: '3.2'
services:
  ...
    environment:
      NOTIFY: "true"
      NOTIFY_URLS: "discord://Dockcheck-web@xxxxx/xxxxxx"
  ...
```


### Example for multiple urls

```yml
version: '3.2'
services:
  ...
    environment:
      NOTIFY: "true"
      NOTIFY_URLS: "discord://Dockcheck-web@xxxxx/xxxxxx tgram://0123456789:RandomLettersAndNumbers-2morestuff-123456789"
  ...
```


### Tested
| Service | Tested | Result | 
| --- | :---: | :---: | 
| Discord | 2023-02-24 | Success |
| Mail | 2023-02-24 | Success | 
| Telegram | 2023-02-24 | Success | 




| Notification Service | Service ID | Default Port | Example Syntax |
| -------------------- | ---------- | ------------ | -------------- |
| [Apprise API](https://github.com/caronc/apprise/wiki/Notify_apprise_api)  | apprise:// or apprises:// | (TCP) 80 or 443 | apprise://hostname/Token
| [AWS SES](https://github.com/caronc/apprise/wiki/Notify_ses)  | ses://   | (TCP) 443   | ses://user@domain/AccessKeyID/AccessSecretKey/RegionName<br/>ses://user@domain/AccessKeyID/AccessSecretKey/RegionName/email1/email2/emailN
| [Bark](https://github.com/caronc/apprise/wiki/Notify_bark)  | bark://   | (TCP) 80 or 443   | bark://hostname<br />bark://hostname/device_key<br />bark://hostname/device_key1/device_key2/device_keyN
| [Boxcar](https://github.com/caronc/apprise/wiki/Notify_boxcar)  | boxcar://   | (TCP) 443   | boxcar://hostname<br />boxcar://hostname/@tag<br/>boxcar://hostname/device_token<br />boxcar://hostname/device_token1/device_token2/device_tokenN<br />boxcar://hostname/@tag/@tag2/device_token
| [Discord](https://github.com/caronc/apprise/wiki/Notify_discord)  | discord://   | (TCP) 443   | discord://webhook_id/webhook_token<br />discord://avatar@webhook_id/webhook_token
| [Emby](https://github.com/caronc/apprise/wiki/Notify_emby)  | emby:// or embys:// | (TCP) 8096 | emby://user@hostname/<br />emby://user:password@hostname
| [Enigma2](https://github.com/caronc/apprise/wiki/Notify_enigma2)  | enigma2:// or enigma2s:// | (TCP) 80 or 443 | enigma2://hostname
| [Faast](https://github.com/caronc/apprise/wiki/Notify_faast) | faast://    | (TCP) 443    | faast://authorizationtoken
| [FCM](https://github.com/caronc/apprise/wiki/Notify_fcm) | fcm://    | (TCP) 443    | fcm://project@apikey/DEVICE_ID<br />fcm://project@apikey/#TOPIC<br/>fcm://project@apikey/DEVICE_ID1/#topic1/#topic2/DEVICE_ID2/
| [Flock](https://github.com/caronc/apprise/wiki/Notify_flock) | flock://    | (TCP) 443    | flock://token<br/>flock://botname@token<br/>flock://app_token/u:userid<br/>flock://app_token/g:channel_id<br/>flock://app_token/u:userid/g:channel_id
| [Gitter](https://github.com/caronc/apprise/wiki/Notify_gitter) | gitter://    | (TCP) 443    | gitter://token/room<br/>gitter://token/room1/room2/roomN
| [Google Chat](https://github.com/caronc/apprise/wiki/Notify_googlechat) | gchat://    | (TCP) 443    | gchat://workspace/key/token
| [Gotify](https://github.com/caronc/apprise/wiki/Notify_gotify) | gotify:// or gotifys://   | (TCP) 80 or 443    | gotify://hostname/token<br />gotifys://hostname/token?priority=high
| [Growl](https://github.com/caronc/apprise/wiki/Notify_growl)  | growl://   | (UDP) 23053   | growl://hostname<br />growl://hostname:portno<br />growl://password@hostname<br />growl://password@hostname:port</br>**Note**: you can also use the get parameter _version_ which can allow the growl request to behave using the older v1.x protocol. An example would look like: growl://hostname?version=1
| [Guilded](https://github.com/caronc/apprise/wiki/Notify_guilded)  | guilded://   | (TCP) 443   | guilded://webhook_id/webhook_token<br />guilded://avatar@webhook_id/webhook_token
| [Home Assistant](https://github.com/caronc/apprise/wiki/Notify_homeassistant)       | hassio:// or hassios://   | (TCP) 8123 or 443 | hassio://hostname/accesstoken<br />hassio://user@hostname/accesstoken<br />hassio://user:password@hostname:port/accesstoken<br />hassio://hostname/optional/path/accesstoken
| [IFTTT](https://github.com/caronc/apprise/wiki/Notify_ifttt) | ifttt://    | (TCP) 443    | ifttt://webhooksID/Event<br />ifttt://webhooksID/Event1/Event2/EventN<br/>ifttt://webhooksID/Event1/?+Key=Value<br/>ifttt://webhooksID/Event1/?-Key=value1
| [Join](https://github.com/caronc/apprise/wiki/Notify_join) | join://   | (TCP) 443    | join://apikey/device<br />join://apikey/device1/device2/deviceN/<br />join://apikey/group<br />join://apikey/groupA/groupB/groupN<br />join://apikey/DeviceA/groupA/groupN/DeviceN/
| [KODI](https://github.com/caronc/apprise/wiki/Notify_kodi) | kodi:// or kodis://    | (TCP) 8080 or 443   | kodi://hostname<br />kodi://user@hostname<br />kodi://user:password@hostname:port
| [Kumulos](https://github.com/caronc/apprise/wiki/Notify_kumulos) | kumulos:// | (TCP) 443 | kumulos://apikey/serverkey
| [LaMetric Time](https://github.com/caronc/apprise/wiki/Notify_lametric) | lametric:// | (TCP) 443 | lametric://apikey@device_ipaddr<br/>lametric://apikey@hostname:port<br/>lametric://client_id@client_secret
| [Line](https://github.com/caronc/apprise/wiki/Notify_line) | line:// | (TCP) 443 | line://Token@User<br/>line://Token/User1/User2/UserN
| [Mailgun](https://github.com/caronc/apprise/wiki/Notify_mailgun) | mailgun:// | (TCP) 443 | mailgun://user@hostname/apikey<br />mailgun://user@hostname/apikey/email<br />mailgun://user@hostname/apikey/email1/email2/emailN<br />mailgun://user@hostname/apikey/?name="From%20User"
| [Mastodon](https://github.com/caronc/apprise/wiki/Notify_mastodon) | mastodon:// or mastodons://| (TCP) 80 or 443  | mastodon://access_key@hostname<br />mastodon://access_key@hostname/@user<br />mastodon://access_key@hostname/@user1/@user2/@userN
| [Matrix](https://github.com/caronc/apprise/wiki/Notify_matrix) | matrix:// or matrixs://  | (TCP) 80 or 443 | matrix://hostname<br />matrix://user@hostname<br />matrixs://user:pass@hostname:port/#room_alias<br />matrixs://user:pass@hostname:port/!room_id<br />matrixs://user:pass@hostname:port/#room_alias/!room_id/#room2<br />matrixs://token@hostname:port/?webhook=matrix<br />matrix://user:token@hostname/?webhook=slack&format=markdown
| [Mattermost](https://github.com/caronc/apprise/wiki/Notify_mattermost) | mmost:// or mmosts:// | (TCP) 8065 | mmost://hostname/authkey<br />mmost://hostname:80/authkey<br />mmost://user@hostname:80/authkey<br />mmost://hostname/authkey?channel=channel<br />mmosts://hostname/authkey<br />mmosts://user@hostname/authkey<br />
| [Microsoft Teams](https://github.com/caronc/apprise/wiki/Notify_msteams) | msteams://  | (TCP) 443   | msteams://TokenA/TokenB/TokenC/
| [Misskey](https://github.com/caronc/apprise/wiki/Notify_misskey) | misskey:// or misskeys://| (TCP) 80 or 443  | misskey://access_token@hostname
| [MQTT](https://github.com/caronc/apprise/wiki/Notify_mqtt) | mqtt://  or mqtts:// | (TCP) 1883 or 8883   | mqtt://hostname/topic<br />mqtt://user@hostname/topic<br />mqtts://user:pass@hostname:9883/topic
| [Nextcloud](https://github.com/caronc/apprise/wiki/Notify_nextcloud) | ncloud:// or nclouds:// | (TCP) 80 or 443 | ncloud://adminuser:pass@host/User<br/>nclouds://adminuser:pass@host/User1/User2/UserN
| [NextcloudTalk](https://github.com/caronc/apprise/wiki/Notify_nextcloudtalk) | nctalk:// or nctalks:// | (TCP) 80 or 443 | nctalk://user:pass@host/RoomId<br/>nctalks://user:pass@host/RoomId1/RoomId2/RoomIdN
| [Notica](https://github.com/caronc/apprise/wiki/Notify_notica) | notica://  | (TCP) 443   | notica://Token/
| [Notifico](https://github.com/caronc/apprise/wiki/Notify_notifico) | notifico://  | (TCP) 443   | notifico://ProjectID/MessageHook/
| [ntfy](https://github.com/caronc/apprise/wiki/Notify_ntfy) | ntfy://  | (TCP) 80 or 443   | ntfy://topic/<br/>ntfys://topic/
| [Office 365](https://github.com/caronc/apprise/wiki/Notify_office365) | o365://  | (TCP) 443   | o365://TenantID:AccountEmail/ClientID/ClientSecret<br />o365://TenantID:AccountEmail/ClientID/ClientSecret/TargetEmail<br />o365://TenantID:AccountEmail/ClientID/ClientSecret/TargetEmail1/TargetEmail2/TargetEmailN
| [OneSignal](https://github.com/caronc/apprise/wiki/Notify_onesignal) | onesignal:// | (TCP) 443 | onesignal://AppID@APIKey/PlayerID<br/>onesignal://TemplateID:AppID@APIKey/UserID<br/>onesignal://AppID@APIKey/#IncludeSegment<br/>onesignal://AppID@APIKey/Email
| [Opsgenie](https://github.com/caronc/apprise/wiki/Notify_opsgenie) | opsgenie:// | (TCP) 443 | opsgenie://APIKey<br/>opsgenie://APIKey/UserID<br/>opsgenie://APIKey/#Team<br/>opsgenie://APIKey/\*Schedule<br/>opsgenie://APIKey/^Escalation
| [PagerDuty](https://github.com/caronc/apprise/wiki/Notify_pagerduty) | pagerduty:// | (TCP) 443 | pagerduty://IntegrationKey@ApiKey<br/>pagerduty://IntegrationKey@ApiKey/Source/Component
| [PagerTree](https://github.com/caronc/apprise/wiki/Notify_pagertree) | pagertree:// | (TCP) 443 | pagertree://integration_id
| [ParsePlatform](https://github.com/caronc/apprise/wiki/Notify_parseplatform) | parsep:// or parseps:// | (TCP) 80 or 443 | parsep://AppID:MasterKey@Hostname<br/>parseps://AppID:MasterKey@Hostname
| [PopcornNotify](https://github.com/caronc/apprise/wiki/Notify_popcornnotify) | popcorn://  | (TCP) 443   | popcorn://ApiKey/ToPhoneNo<br/>popcorn://ApiKey/ToPhoneNo1/ToPhoneNo2/ToPhoneNoN/<br/>popcorn://ApiKey/ToEmail<br/>popcorn://ApiKey/ToEmail1/ToEmail2/ToEmailN/<br/>popcorn://ApiKey/ToPhoneNo1/ToEmail1/ToPhoneNoN/ToEmailN
| [Prowl](https://github.com/caronc/apprise/wiki/Notify_prowl) | prowl://   | (TCP) 443    | prowl://apikey<br />prowl://apikey/providerkey
| [PushBullet](https://github.com/caronc/apprise/wiki/Notify_pushbullet) | pbul://    | (TCP) 443    | pbul://accesstoken<br />pbul://accesstoken/#channel<br/>pbul://accesstoken/A_DEVICE_ID<br />pbul://accesstoken/email@address.com<br />pbul://accesstoken/#channel/#channel2/email@address.net/DEVICE
| [Pushjet](https://github.com/caronc/apprise/wiki/Notify_pushjet) | pjet:// or pjets:// | (TCP) 80 or 443 | pjet://hostname/secret<br />pjet://hostname:port/secret<br />pjets://secret@hostname/secret<br />pjets://hostname:port/secret
| [Push (Techulus)](https://github.com/caronc/apprise/wiki/Notify_techulus) | push://    | (TCP) 443    | push://apikey/
| [Pushed](https://github.com/caronc/apprise/wiki/Notify_pushed) | pushed://    | (TCP) 443    | pushed://appkey/appsecret/<br/>pushed://appkey/appsecret/#ChannelAlias<br/>pushed://appkey/appsecret/#ChannelAlias1/#ChannelAlias2/#ChannelAliasN<br/>pushed://appkey/appsecret/@UserPushedID<br/>pushed://appkey/appsecret/@UserPushedID1/@UserPushedID2/@UserPushedIDN
| [Pushover](https://github.com/caronc/apprise/wiki/Notify_pushover)  | pover://   | (TCP) 443   | pover://user@token<br />pover://user@token/DEVICE<br />pover://user@token/DEVICE1/DEVICE2/DEVICEN<br />**Note**: you must specify both your user_id and token
| [PushSafer](https://github.com/caronc/apprise/wiki/Notify_pushsafer)  | psafer:// or psafers://  | (TCP) 80 or 443  | psafer://privatekey<br />psafers://privatekey/DEVICE<br />psafer://privatekey/DEVICE1/DEVICE2/DEVICEN
| [Reddit](https://github.com/caronc/apprise/wiki/Notify_reddit) | reddit:// | (TCP) 443   | reddit://user:password@app_id/app_secret/subreddit<br />reddit://user:password@app_id/app_secret/sub1/sub2/subN
| [Rocket.Chat](https://github.com/caronc/apprise/wiki/Notify_rocketchat) | rocket:// or rockets://  | (TCP) 80 or 443   | rocket://user:password@hostname/RoomID/Channel<br />rockets://user:password@hostname:443/#Channel1/#Channel1/RoomID<br />rocket://user:password@hostname/#Channel<br />rocket://webhook@hostname<br />rockets://webhook@hostname/@User/#Channel
| [Ryver](https://github.com/caronc/apprise/wiki/Notify_ryver) | ryver://  | (TCP) 443   | ryver://Organization/Token<br />ryver://botname@Organization/Token
| [SendGrid](https://github.com/caronc/apprise/wiki/Notify_sendgrid) | sendgrid://  | (TCP) 443   | sendgrid://APIToken:FromEmail/<br />sendgrid://APIToken:FromEmail/ToEmail<br />sendgrid://APIToken:FromEmail/ToEmail1/ToEmail2/ToEmailN/
| [ServerChan](https://github.com/caronc/apprise/wiki/Notify_serverchan) | schan://   | (TCP) 443    | schan://sendkey/
| [Signal API](https://github.com/caronc/apprise/wiki/Notify_signal) | signal://  or signals:// | (TCP) 80 or 443  | signal://hostname:port/FromPhoneNo<br/>signal://hostname:port/FromPhoneNo/ToPhoneNo<br/>signal://hostname:port/FromPhoneNo/ToPhoneNo1/ToPhoneNo2/ToPhoneNoN/
| [SimplePush](https://github.com/caronc/apprise/wiki/Notify_simplepush) | spush://   | (TCP) 443    | spush://apikey<br />spush://salt:password@apikey<br />spush://apikey?event=Apprise
| [Slack](https://github.com/caronc/apprise/wiki/Notify_slack) | slack://  | (TCP) 443   | slack://TokenA/TokenB/TokenC/<br />slack://TokenA/TokenB/TokenC/Channel<br />slack://botname@TokenA/TokenB/TokenC/Channel<br />slack://user@TokenA/TokenB/TokenC/Channel1/Channel2/ChannelN
| [SMTP2Go](https://github.com/caronc/apprise/wiki/Notify_smtp2go) | smtp2go:// | (TCP) 443 | smtp2go://user@hostname/apikey<br />smtp2go://user@hostname/apikey/email<br />smtp2go://user@hostname/apikey/email1/email2/emailN<br />smtp2go://user@hostname/apikey/?name="From%20User"
| [Streamlabs](https://github.com/caronc/apprise/wiki/Notify_streamlabs) | strmlabs:// | (TCP) 443 | strmlabs://AccessToken/<br/>strmlabs://AccessToken/?name=name&identifier=identifier&amount=0&currency=USD
| [SparkPost](https://github.com/caronc/apprise/wiki/Notify_sparkpost) | sparkpost:// | (TCP) 443 | sparkpost://user@hostname/apikey<br />sparkpost://user@hostname/apikey/email<br />sparkpost://user@hostname/apikey/email1/email2/emailN<br />sparkpost://user@hostname/apikey/?name="From%20User"
| [Spontit](https://github.com/caronc/apprise/wiki/Notify_spontit) | spontit://  | (TCP) 443   | spontit://UserID@APIKey/<br />spontit://UserID@APIKey/Channel<br />spontit://UserID@APIKey/Channel1/Channel2/ChannelN
| [Syslog](https://github.com/caronc/apprise/wiki/Notify_syslog) | syslog://  | (UDP) 514 (_if hostname specified_) | syslog://<br />syslog://Facility<br />syslog://hostname<br />syslog://hostname/Facility
| [Telegram](https://github.com/caronc/apprise/wiki/Notify_telegram) | tgram://  | (TCP) 443   | tgram://bottoken/ChatID<br />tgram://bottoken/ChatID1/ChatID2/ChatIDN
| [Twitter](https://github.com/caronc/apprise/wiki/Notify_twitter) | twitter://  | (TCP) 443   | twitter://CKey/CSecret/AKey/ASecret<br/>twitter://user@CKey/CSecret/AKey/ASecret<br/>twitter://CKey/CSecret/AKey/ASecret/User1/User2/User2<br/>twitter://CKey/CSecret/AKey/ASecret?mode=tweet
| [Twist](https://github.com/caronc/apprise/wiki/Notify_twist) | twist://  | (TCP) 443   | twist://pasword:login<br/>twist://password:login/#channel<br/>twist://password:login/#team:channel<br/>twist://password:login/#team:channel1/channel2/#team3:channel
| [XBMC](https://github.com/caronc/apprise/wiki/Notify_xbmc) | xbmc:// or xbmcs://    | (TCP) 8080 or 443   | xbmc://hostname<br />xbmc://user@hostname<br />xbmc://user:password@hostname:port
| [Webex Teams (Cisco)](https://github.com/caronc/apprise/wiki/Notify_wxteams) | wxteams://  | (TCP) 443   | wxteams://Token
| [Zulip Chat](https://github.com/caronc/apprise/wiki/Notify_zulip) | zulip://  | (TCP) 443   | zulip://botname@Organization/Token<br />zulip://botname@Organization/Token/Stream<br />zulip://botname@Organization/Token/Email


### Mail
This is what worked for me
* NOTIFY_URL: "mailtos://mail.server.com/?user=testuser@domain.com&pass=xxxx"

# TODO List
| TODO | Tested | Result | Implemented |
| --- | :---: | :---: | :---: |
| ARM64 and AMD64 Support | 2023-02-23 | Success | 2023-02-23 |
| Slim version without webgui (Only notifications) | | | |


# Future ideas
| Feature | Timeline | Stage |
| --- | :---: | :---: |
| Notifications | March 2023 | Alpha |
| Update via webgui | Unknown (Need Help) | |
| Slim version without webgui (Only notifications) | March 2023 | | |
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
