# ![image](https://user-images.githubusercontent.com/34389545/56409412-8f1c4600-623e-11e9-961b-ed57382df370.png)

## TurtleCoin .trtl TLD HTTP Resolver API

This repository contains the implementation for a web resolver of .trtl and OpenNIC domains.

## Prerequisites

* [Node.js](https://nodejs.org/) LTS

## Setup

1) Clone this repository to wherever you'd like the API to run:

```bash
git clone https://github.com/turtlecoin/.trtl-resolver
```

2) Install the required Node.js modules

```bash
cd .trtl-resolver && npm install
```

3) Use your favorite text editor to change the values as necessary in `config.json`

```javascript
{
  "bindIp": "0.0.0.0",
  "httpPort": 80,
  "corsHeader": "*",
  "dnsServers": [
    "142.93.1.231",
    "104.248.57.4"
  ]
}

```

4) Fire up the script

```bash
node index.js
```

5) Optionally, install PM2 or another process manager to keep the service running.

```bash
npm install -g pm2@latest
pm2 startup
pm2 start index.js --name trtl-resolver -i max
pm2 save
```

## API Documentation

Coming soon...

###### (c) 2019 The TurtleCoin Developers
