# FITM Hospital Monitoring
<b>IoT WebBased Temperature Monitoring</b> system that can be access anywhere and anytime through the Internet is build. With this system a user can remotely monitor the room temperature from anywhere which could save the human expenses, The main purpose of this system model is to make it easy for the user to view the current temperature.
## Feature
- Set an alert condition will push a notification to your <b>LINE Group</b> (LINE Notify).
- Supports multiple users on a single project.
- Mobile Friendly support Android and iOS Devices.
## Installation
To install, you must have Git and Docker Engine installed already.
### Cloning a Git repository.
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
### Open Web Application
[http://localhost:9000](http://localhost:9000)
### Open Database (phpMyAdmin)
[http://localhost:9001](http://localhost:9001)
- <b>Username:</b> admin
- <b>Password:</b> dinsorsee
### Pushing Temperature Data: POST (HTTP)
```
curl --request POST --url http://localhost:9000/push.php --form token={{ device_token }} --form temp={{ temperature }}
```
- device_token 
- temperature
### Shutdown Web Application
```
docker-compose down
```
### Reset Database
## Tutorial
## LINE Notify
[LINE Notify](https://notify-bot.line.me/th/)