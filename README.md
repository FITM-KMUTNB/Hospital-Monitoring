# FITM Hospital Monitoring
**IoT WebBased Temperature Monitoring** system that can be access anywhere and anytime through the Internet is build. With this system a user can remotely monitor the room temperature from anywhere which could save the human expenses, The main purpose of this system model is to make it easy for the user to view the current temperature.</br></br>
![alt IoT WebBased Temperature Monitoring](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/master/screenshot/mobile.jpg?token=AEtRcpEwYxVAdpiCqoSUwdhGbZb1nMlLks5ca-U9wA%3D%3D)

## Feature
- Set an alert condition will push a notification to your **LINE Group** *(LINE Notify)*
- Supports multiple users on a single project.
- Mobile Friendly support Android and iOS Devices.
![alt Push a notification to your LINE Group](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/fix-readme/screenshot/line-notify.png?token=AEtRcu5ZhHwRlyPab58Qf2u64K5NmcUyks5ca-bEwA%3D%3D)

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
![alt docker-compose up -d --build && docker-compose ps](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/fix-readme/screenshot/docker-compose-up.png?token=AEtRcjdGupS2xk5Bz1PSt5SQmwhy4PrVks5ca-bcwA%3D%3D)

#### Web Application
```
http://localhost:9000
```
![alt Login page](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/fix-readme/screenshot/login.png?token=AEtRcgAFGxFo2OLmW8Z_0qryyI07YtlQks5ca-b1wA%3D%3D)

#### Create a Device
To get started quickly, create your project and create new device.
![alt Create your first project](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/fix-readme/screenshot/project-first-create.png?token=AEtRchoJmv0dv8YWNkpVmzUEAhq9eVubks5ca-cMwA%3D%3D)
List all projects and devices
![alt index page](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/fix-readme/screenshot/index.png?token=AEtRcvEpORAS4niGiwrjAjmey51wBythks5ca-chwA%3D%3D)

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
![alt Find device_token](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/fix-readme/screenshot/device-setting.png?token=AEtRcidIOEZNlzV9XA28VgXnYzZSWnwPks5ca-c7wA%3D%3D)

Pushing with [Insomnia REST client](https://insomnia.rest)
![alt Pushing with Insomnia REST client](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/fix-readme/screenshot/push_data.png?token=AEtRcsBVdecKwlem8haEscSKtyZas2Dcks5ca-dRwA%3D%3D)

## LINE Notify
Set LINE Notify token in to project setting, you have to generate access token on [LINE Notify](https://notify-bot.line.me/th/)
![alt Project setting page](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/fix-readme/screenshot/project-edit.png?token=AEtRcl7Br6jONX30ofKzqQhqUjQlA6ifks5ca-g3wA%3D%3D)

### Shutdown
```
docker-compose down
```
![alt docker-compose down](https://raw.githubusercontent.com/FITM-KMUTNB/Hospital-Monitoring/fix-readme/screenshot/docker-compose-down.png?token=AEtRcpQM2zalshWgLFjoUBzQl1SAv3yBks5ca-hMwA%3D%3D)

### Reset database
```
rm -r docker/mysql/data/*
```
