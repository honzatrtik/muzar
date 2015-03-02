"use strict";

var Promise = require('../promise.js');
var superagent = require('../superagent.js');
var BaseStore = require('./base-store.js');
var RouteStore = require('./route-store.js');
var HttpError = require('../errors/http-error.js');
var Imm = require('immutable');
var _ = require('lodash');

var req;
function load(id) {
    req && req.abort();
    req = superagent.get('/ads/' + id);
    return new Promise(function(resolve, reject) {
        req.end(function(res) {
            if (res.ok) {
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
            if (store.getRoute() == 'detail') {

                var id = store.getParam().id;

                self.getBinding().atomically()
                    .set('id', id)
                    .remove('data')
                    .commit();

                return load(store.getParam().id).then(function(data) {
                    self.getBinding().set('data', Imm.Map(data.data));
                }).catch(function(e) {
                    throw e;
                });
            }

        });
    }
};

module.exports = AdDetailStore;