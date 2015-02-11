"use strict";

var BaseStore = require('./base-store.js');
var RouteStore = require('./route-store.js');

class AdStore extends BaseStore {

}

AdStore.storeName = 'AdStore';
AdStore.handlers = {
    'ROUTE_CHANGED': function() {
        var self = this;
        this.dispatcher.waitFor(RouteStore, function() {
        });
    }
};

module.exports = AdStore;