/* eslint-disable max-len */
/* eslint linebreak-style: ['error', "windows"] */

const bodyParser = require('body-parser'); // Used to Parse Body of Comming Result
const express = require('express'); // Express Server ({Important})
const app = express();

const rateLimit = require('express-rate-limit'); // Limit the rate of API hits

const xss = require('xss-clean'); // Cross Site Scripting Cleaner
const expressSanitizer = require('express-sanitizer'); // Cross Site Scripting Cleaner by Express
const cors = require('cors'); // Connection Middleware

// API URL
const apiUrl = '192.168.10.4';

// enables cors
app.use(cors({
  'allowedHeaders': ['sessionId', 'Content-Type'],
  'exposedHeaders': ['sessionId'],
  'origin': '*',
  'methods': 'POST',
  'preflightContinue': false,
}));

const limit = rateLimit({
  max: 1000, // Max requests
  windowMs: 1000, // 1 Second
  message: 'Server Overloaded, Try Again Later', // message to send
});

// Prevent Cross Site Scripting
app.use(xss());
app.use(expressSanitizer());

// Prevent DDOS Attack
app.use('/', limit);

// Prevent Large Input
app.use( express.json({limit: '5kb'}));

// Revice Result from Server
app.use( bodyParser.urlencoded({extended: true}));

// Start Server
const server = app.listen(8000, apiUrl, function() {
  const host = server.address().address;
  const port = server.address().port;
  console.log('App listening at http://%s:%s', host, port);
});

module.exports = app;
