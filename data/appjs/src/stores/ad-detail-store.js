"use strict";

var Promise = require('../promise.js');
var superagent = require('../superagent.js');
var BaseStore = require('./base-store.js');
var RouteStore = require('./route-store.js');
var HttpError = require('../errors/http-error.js');
var Imm = require('immutable');


var cache = Imm.Map({});
var req;

function clearCache(id) {
    cache = id
        ? cache.remove(id)
        : cache.clear();
}

function load(id) {

    if (cache.has(id)) {
        return Promise.resolve(cache.get(id));
    }

    req && req.abort();
    req = superagent.get('/ads/' + id);
    return new Promise(function(resolve, reject) {
        req.end(function(res) {
            if (res.ok) {
                cache = cache.set(id, res.body);
                resolve(res.body);
            } else {
                reject(new HttpError(res.error.status, res.error.text));
            }
        });
    });
}


class AdDetailStore extends BaseStore {
    getAd() {
        return this.getBinding().get('data');
    }
}

AdDetailStore.storeName = 'AdDetailStore';
AdDetailStore.handlers = {
    'ROUTE_CHANGED': function() {
        var self = this;
        return this.dispatcher.waitForPromises(RouteStore, function() {

            var store = self.getStore(RouteStore);
            if (store.getRoute() == 'detail' || store.getRoute() == 'detailReply') {

                var id = store.getParam('id');

                self.getBinding().atomically()
                    .set('id', id)
                    .remove('data')
                    .commit();

                return load(id).then(function(data) {
                    self.getBinding().set('data', Imm.fromJS(data.data));
                }).catch(function(e) {
                    throw e;
                });
            } else {
                clearCache();
            }

        });
    }
};

module.exports = AdDetailStore;