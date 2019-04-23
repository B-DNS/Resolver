// Portions Copyright (c) 2018-2019, The TurtlePay Developers
// Copyright (c) 2019, The TurtleCoin Developers
//
// Please see the included LICENSE file for more information.

'use strict'

const Compression = require('compression')
const Config = require('./config.json')
const DNS = require('dns')
const Express = require('express')
const Helmet = require('helmet')
const util = require('util')

/* Let's set up a standard logger. Sure it looks cheap but it's
   reliable and won't crash */
function log (message) {
  console.log(util.format('%s: %s', (new Date()).toUTCString(), message))
}

function logHTTPRequest (req, params) {
  params = params || ''
  log(util.format('[REQUEST] (%s) %s %s', req.ip, req.path, params))
}

function logHTTPError (req, message) {
  message = message || 'Parsing error'
  log(util.format('[ERROR] (%s) %s: %s', req.ip, req.path, message))
}

/* Set our default servers to those provided in
   https://github.com/turtlecoin/.trtl/blob/master/config/domain-config */
DNS.setServers(Config.dnsServers)

/* Init a nice helper function that helps us resolve
   hostnames via the given nameservers */
function resolveHostname (hostname, type) {
  type = type || 'ANY'
  type = type.toUpperCase()

  return new Promise((resolve, reject) => {
    DNS.resolve(hostname, type, (err, records) => {
      if (err) return reject(err)

      return resolve(records)
    })
  })
}

const app = Express()

/* Set up a few of our headers to make this API more functional */
app.use((req, res, next) => {
  res.header('X-Requested-With', '*')
  res.header('Access-Control-Allow-Origin', Config.corsHeader)
  res.header('Access-Control-Allow-Headers', 'Origin, X-Requested-With, Content-Type, Accept')
  res.header('Access-Control-Allow-Methods', 'GET, OPTIONS')
  res.header('Cache-Control', 'max-age=30, public')
  next()
})

/* Set up our system to use Helmet */
app.use(Helmet())

/* Last but certainly not least, enable compression because we're going to need it */
app.use(Compression())

app.get('/favicon.ico', (request, response) => {
  return response.status(404).send()
})

app.get('/robots.txt', (request, response) => {
  return response.status(404).send()
})

app.get('/:hostname/:type', (request, response) => {
  const hostname = request.params.hostname
  const type = request.params.type

  resolveHostname(hostname, type).then((records) => {
    logHTTPRequest(request)
    return response.json(records)
  }).catch(() => {
    logHTTPError(request, 'Error retrieving DNS information')
    return response.status(504).send()
  })
})

app.get('/:hostname', (request, response) => {
  const hostname = request.params.hostname

  resolveHostname(hostname).then((records) => {
    logHTTPRequest(request)
    return response.json(records)
  }).catch(() => {
    logHTTPError(request, 'Error retrieving DNS information')
    return response.status(504).send()
  })
})

/* Response to options requests for preflights */
app.options('*', (req, res) => {
  return res.status(200).send()
})

/* This is our catch all to return a 404-error */
app.all('*', (req, res) => {
  logHTTPError(req, 'Requested URL not Found (404)')
  return res.status(404).send()
})

app.listen(Config.httpPort, Config.bindIp, () => {
  log('HTTP server started on ' + Config.bindIp + ':' + Config.httpPort)
})
