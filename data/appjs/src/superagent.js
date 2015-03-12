"use strict";

var config = require('../config.js');
var superagent = require('superagent');
var Promise = require('./promise.js');

var end = superagent.Request.prototype.end;
superagent.Request.prototype.end = function() {
    this.emit('beforerequest', this);
    end.apply(this, arguments);
};

superagent.Request.prototype.promise = function() {
    var self = this;
    return new Promise(function(resolve, reject) {
        self.end(function(res) {
            if (res.ok) {
                resolve(res.body);
            } else {
                reject(res);
            }
        });
    });
};


var plugins = [];
var alteredSuperagent = function() {

    var request = superagent.apply(superagent, arguments);
    plugins.forEach(plugin => request.use(plugin));
    return request;

};

alteredSuperagent.use = function(fn) {
    plugins.push(fn);
    return alteredSuperagent;
};


['get', 'post', 'delete', 'put', 'del', 'patch'].forEach(function(name) {
    alteredSuperagent[name] = function() {
        var request = superagent[name].apply(superagent, arguments);
        plugins.forEach(plugin => request.use(plugin));
        return request;
    }
});




alteredSuperagent.use(function(request) {

    request.on('beforerequest', function(req) {

        if (req.url[0] === '/') {
            req.url = config.api.urlPrefix + req.url;
        }
        req.accept('application/json');

    });

});







module.exports = alteredSuperagent;