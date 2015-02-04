"use strict";

var BaseStore = require('./base-store.js');

class RouteStore extends BaseStore {
    getState() {
        return this.binding.toJS('state');
    }
}

RouteStore.storeName = 'RouteStore';
RouteStore.handlers = {
    'ROUTE_CHANGED': function(state) {
        this.binding.set('state', state);
    }
};

module.exports = RouteStore;