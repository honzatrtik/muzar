"use strict";

var Promise = require('es6-promise').Promise;
var Imm = require('immutable');
var superagent = require('../superagent.js');
var BaseStore = require('./base-store.js');
var RouteStore = require('./route-store.js');


var req;
function load() {
    req && req.abort();
    req = superagent.get('/categories');
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





class CategoryStore extends BaseStore {

    getItems() {
        return this.getBinding().get('items');
    }

    getActive() {
        return this.getBinding().toJS('active');
    }
}

CategoryStore.storeName = 'CategoryStore';
CategoryStore.handlers = {
    'SERVER_INIT': function() {
        var self = this;
        return load().then(function(data) {
            self.getBinding().set('items', Imm.fromJS(data.data));
        });
    },

    'ROUTE_CHANGED': function() {
        var self = this;
        return this.dispatcher.waitFor(RouteStore, function() {
            var store = self.getStore(RouteStore);
            if (store.getRoute() == 'list') {
                var category = store.getParams().category;
                self.getBinding().set('active', category);
            }
        });
    }
};

module.exports = CategoryStore;