"use strict";

var BaseStore = require('./base-store.js');
var CategoryStore = require('./category-store.js');
var Promise = require('es6-promise').Promise;
var superagent = require('../superagent.js');
var HttpError = require('../errors/http-error.js');
var Imm = require('immutable');
var _ = require('lodash');

var req;
function load(params) {
    req && req.abort();
    req = superagent.get('/ads');
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


class AdStore extends BaseStore {
    getItems() {
        return this.getBinding().get('items');
    }
}

AdStore.storeName = 'AdStore';
AdStore.handlers = {
    'ROUTE_CHANGED': function() {
        var self = this;
        return this.dispatcher.waitForPromises(CategoryStore, function() {

            var store = self.getStore(CategoryStore);
            return load({
                category: store.getActive()
            }).then(function(data) {
                self.getBinding().set('items', Imm.fromJS(data.data));
            });

        });
    }
};

module.exports = AdStore;