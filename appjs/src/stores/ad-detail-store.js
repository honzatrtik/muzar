"use strict";

var Promise = require('es6-promise').Promise;
var superagent = require('superagent');
var BaseStore = require('./base-store.js');
var RouteStore = require('./route-store.js');


var req;
function load(id) {
    req && req.abort();
    req = superagent.get('http://localhost:3030/ads/' + id);
    req.accept('application/json');

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


class AdDetailStore extends BaseStore {
    getAd() {
        return this.getBinding().get();
    }
}

AdDetailStore.storeName = 'AdDetailStore';
AdDetailStore.handlers = {
    'ROUTE_CHANGED': function() {
        var self = this;
        this.dispatcher.waitFor(RouteStore, function() {
            var store = self.getStore(RouteStore);
            if (store.getRoute() == 'detail') {
                return load(store.getParams().id).then(function(data) {
                    self.getBinding().set(data.data);
                }).catch(function(res) {
                    throw new Error(res.status);
                });
            }
        });
    }
};

module.exports = AdDetailStore;