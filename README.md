# FITM Hospital Monitoring
**IoT WebBased Temperature Monitoring** system that can be access anywhere and anytime through the Internet is build. With this system a user can remotely monitor the room temperature from anywhere which could save the human expenses, The main purpose of this system model is to make it easy for the user to view the current temperature.
## Feature
- Set an alert condition will push a notification to your **LINE Group** *(LINE Notify)*
- Supports multiple users on a single project.
- Mobile Friendly support Android and iOS Devices.
## Installation
To install, you must have **Git** and **Docker Engine** installed already.
### Cloning a Repository.
```
git clone https://github.com/FITM-KMUTNB/Hospital-Monitoring.git
```
### Start project with docker-compose
```
cd Hospital-Monitoring
```
### Build and Start
```
docker-compose up -d --build && docker-compose ps
```
![alt docker-compose up -d --build && docker-compose ps](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/readme/screenshot/docker-compose-up.png?token=AEtRcsKREVrDVkVBII6-doeVQ2BjKGbbks5ca2D2wA%3D%3D)
**Web Application**
```
http://localhost:9000
```
**Database** *(phpMyAdmin)*
```
http://localhost:9001
```
- Username: *admin*
- Password: *dinsorsee*
### Pushing temperature data POST (HTTP)
```
curl --request POST --url http://localhost:9000/push.php --form token={{ device_token }} --form temp={{ temperature }}
```
- **device_token** — Is a unique key for the ioT devices, **Example:** *754a0148dc1d37069dc011d4a5fa04bbd*
- **temperature** — Can send number *4* or *5.3* or *-12*
![alt Pushing with Insomnia REST client](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/readme/screenshot/push_data.png?token=AEtRcsbboJdJM-XJ3oZx9cefOK-jY9Riks5caoz0wA%3D%3D)
### Shutdown
```
docker-compose down
```
![alt docker-compose down](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/readme/screenshot/docker-compose-down.png?token=AEtRch6uIQoMwEWsCcZf32K3USV96ohCks5ca2DswA%3D%3D)
### Reset Database
## Tutorial
## LINE Notify
[LINE Notify](https://notify-bot.line.me/th/)