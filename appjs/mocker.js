var raml = require('raml-mocker-server');
var cors = require('cors');
var express = require('express');
var app = express();

raml({
    path: __dirname + '/raml',
    app: app
}, function() {});

app.use(function(req, res, next) {
    res.setHeader('Content-Type', 'application/json');
    next();
});
app.use(cors());

var server = app.listen(3030, '127.0.0.1', function() {

    var host = server.address().address;
    var port = server.address().port;

    console.log('Api listening at http://%s:%s', host, port);

});