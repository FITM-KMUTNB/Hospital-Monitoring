# FITM Hospital Monitoring
**IoT WebBased Temperature Monitoring** system that can be access anywhere and anytime through the Internet is build. With this system a user can remotely monitor the room temperature from anywhere which could save the human expenses, The main purpose of this system model is to make it easy for the user to view the current temperature.</br></br>
![alt IoT WebBased Temperature Monitoring](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/master/screenshot/mobile.jpg?token=AEtRcpEwYxVAdpiCqoSUwdhGbZb1nMlLks5ca-U9wA%3D%3D)

## Feature
- Set an alert condition will push a notification to your **LINE Group** *(LINE Notify)*
- Supports multiple users on a single project.
- Mobile Friendly support Android and iOS Devices.</br>
![alt Push a notification to your LINE Group](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/master/screenshot/line-notify.png)

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
![alt docker-compose up -d --build && docker-compose ps](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/master/screenshot/docker-compose-up.png)

#### Web Application
```
http://localhost
```
![alt Login page](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/master/screenshot/login.png)

#### Create a Device
To get started quickly, create your project and create new device.</br>
![alt Create your first project](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/master/screenshot/project-first-create.png)
</br>List all projects and devices</br>
![alt index page](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/master/screenshot/index.png)

#### Database (phpMyAdmin)
```
http://localhost:9001
```
- Username: *admin*
- Password: *dinsorsee*

### Pushing temperature data POST (HTTP)
```
curl --request POST --url http://localhost:9000/push.php --header 'content-type: application/json' --data '{"token": "DEVICE_TOPKEN","temp": TEMPERATURE}'
```
- **DEVICE_TOPKEN** — Is a unique key for the ioT devices.
- **TEMPERATURE** — Can send number *4* or *5.3* or *-12*

##### Device Token
You need to get a device token for push temperature to server. With this token you can view Device settings.</br>
**Example:** *754a0148dc1d37069dc011d4a5fa04bbd*</br>
![alt Find device_token](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/master/screenshot/device-setting.png)

Pushing with [Insomnia REST client](https://insomnia.rest)</br>
![alt Pushing with Insomnia REST client](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/master/screenshot/push_data.png)

## LINE Notify
Set LINE Notify token in to project setting, you have to generate access token on [LINE Notify](https://notify-bot.line.me/th/)</br>
![alt Project setting page](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/master/screenshot/project-edit.png)

### Shutdown
```
docker-compose down
```
![alt docker-compose down](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/master/screenshot/docker-compose-down.png)

### Reset database
```
rm -r docker/mysql/data/*
```
