"use strict";

var raml = require('raml-mocker-server');
var cors = require('cors');
var express = require('express');
var app = express();

raml({
    path: __dirname + '/../doc/raml',
    app: app
}, function() {});

app.use(function(req, res, next) {
    res.setHeader('Content-Type', 'application/json');
    next();
});
app.use(cors());

module.exports = app;
