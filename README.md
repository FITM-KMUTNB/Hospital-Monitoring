# FITM Hospital Monitoring
**IoT WebBased Temperature Monitoring** system that can be access anywhere and anytime through the Internet is build. With this system a user can remotely monitor the room temperature from anywhere which could save the human expenses, The main purpose of this system model is to make it easy for the user to view the current temperature.</br></br>
![alt IoT WebBased Temperature Monitoring](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/readme/screenshot/mobile.jpg?token=AEtRcsr3_vNEJAhycQCcTJOfiHoQhwyMks5ca4ciwA%3D%3D)
## Feature
- Set an alert condition will push a notification to your **LINE Group** *(LINE Notify)*
- Supports multiple users on a single project.
- Mobile Friendly support Android and iOS Devices.
![alt Push a notification to your LINE Group](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/readme/screenshot/line-notify.png?token=AEtRclAV4_jc_j91FZPNmH0DXLOmUpDdks5ca2J1wA%3D%3D)
## Installation
To install, you must have **Git** and **Docker Engine** installed already.
### Cloning a Repository.
```
git clone https://github.com/FITM-KMUTNB/Hospital-Monitoring.git
```
### Change the current directory to Project
```
cd Hospital-Monitoring
```
### Build and Start with docker-compose
```
docker-compose up -d --build && docker-compose ps
```
![alt docker-compose up -d --build && docker-compose ps](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/readme/screenshot/docker-compose-up.png?token=AEtRcsKREVrDVkVBII6-doeVQ2BjKGbbks5ca2D2wA%3D%3D)
#### Web Application
```
http://localhost:9000
```
![alt Login page](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/readme/screenshot/login.png?token=AEtRcpKM0Mi5qYNAec18aMDIoUbLOiX9ks5ca48XwA%3D%3D)
![alt index page](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/readme/screenshot/index.png?token=AEtRcvO-i626gLOeMUnfEhtgcAV9GgYwks5ca48pwA%3D%3D)
#### Database (phpMyAdmin)
```
http://localhost:9001
```
- Username: *admin*
- Password: *dinsorsee*
### Pushing temperature data POST (HTTP)
```
curl --request POST --url http://localhost:9000/push.php --form token={{ device_token }} --form temp={{ temperature }}
```
- **device_token** — Is a unique key for the ioT devices.
- **temperature** — Can send number *4* or *5.3* or *-12*
##### Device Token
You need to get a device token for push temperature to server. With this token you can view Device settings.</br>
**Example:** *754a0148dc1d37069dc011d4a5fa04bbd*
![alt Find device_token](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/readme/screenshot/device-setting.png?token=AEtRchArIrDO8Tppn3oa5nlEQUYeiXgUks5ca6xwwA%3D%3D)

Pushing with [Insomnia REST client](https://insomnia.rest)
![alt Pushing with Insomnia REST client](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/readme/screenshot/push_data.png?token=AEtRcsbboJdJM-XJ3oZx9cefOK-jY9Riks5caoz0wA%3D%3D)
## LINE Notify
Set LINE Notify token in to project setting, you have to generate access token on [LINE Notify](https://notify-bot.line.me/th/)
![alt Project setting page](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/readme/screenshot/project-edit.png?token=AEtRcuuPSUKvJI0NrMFaowQrPVuCiFd3ks5ca7SRwA%3D%3D)
### Shutdown
```
docker-compose down
```
![alt docker-compose down](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/readme/screenshot/docker-compose-down.png?token=AEtRch6uIQoMwEWsCcZf32K3USV96ohCks5ca2DswA%3D%3D)

### Reset database
```
rm -r docker/mysql/data/*
```