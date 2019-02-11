# FITM Hospital Monitoring
<b>IoT WebBased Temperature Monitoring</b> system that can be access anywhere and anytime through the Internet is build. With this system a user can remotely monitor the room temperature from anywhere which could save the human expenses, The main purpose of this system model is to make it easy for the user to view the current temperature.
## Feature
- Set an alert condition will push a notification to your <b>LINE Group</b> (LINE Notify).
- Supports multiple users on a single project.
- Mobile Friendly support Android and iOS Devices.
## Installation
To install, you must have Git and Docker Engine installed already.
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
### Web Application
[http://localhost:9000](http://localhost:9000)
### Database (phpMyAdmin)
[http://localhost:9001](http://localhost:9001)
- <b>Username:</b> admin
- <b>Password:</b> dinsorsee
### Pushing temperature data POST (HTTP)
```
curl --request POST --url http://localhost:9000/push.php --form token={{ device_token }} --form temp={{ temperature }}
```
- <b>device_token</b> — is a unique key for the ioT devices, Example: 754a0148dc1d37069dc011d4a5fa04bbd
- <b>temperature</b> — you can send number 4 or 5.3 or -12
![alt Pushing with Insomnia REST client](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/readme/screenshot/push_data.png?token=AEtRcsbboJdJM-XJ3oZx9cefOK-jY9Riks5caoz0wA%3D%3D)
### Shutdown
```
docker-compose down
```
### Reset Database
## Tutorial
## LINE Notify
[LINE Notify](https://notify-bot.line.me/th/)