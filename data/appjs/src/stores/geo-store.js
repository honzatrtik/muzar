"use strict";

var Promise = require('../promise.js');
var Imm = require('immutable');
var superagent = require('../superagent.js');
var BaseStore = require('./base-store.js');
var HttpError = require('../errors/http-error.js');


var req;
function load() {
    req && req.abort();
    req = superagent.get('/geo/tree');
    return new Promise(function(resolve, reject) {
        req.end(function(res) {
            if (res.ok) {
                resolve(res.body);
            } else {
                reject(res);
            }
        });
    });
}




class GeoStore extends BaseStore {

    getItems() {
        return this.getBinding().get('items') || Imm.List();
    }

}

GeoStore.storeName = 'GeoStore';
GeoStore.handlers = {
    'SERVER_INIT': function() {
        var self = this;
        return load().then(function(data) {
            self.getBinding().set('items', Imm.fromJS(data.data));
        });
    }

};

module.exports = GeoStore;