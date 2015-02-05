"use strict";

var Imm = require('immutable');
var _ = require('lodash');
var BaseStore = require('./base-store.js');

class RouteStore extends BaseStore {

    getPath() {
        return this.binding.toJS('state.path');
    }

    getPathname() {
        return this.binding.toJS('state.pathname');
    }

    getParams() {
        return this.binding.toJS('state.params');
    }

    getQuery() {
        return this.binding.toJS('state.query');
    }

    getRoutes() {
        return this.binding.toJS('state.routes');
    }

    getRoute() {
        return _.last(this.getRoutes())
    }
}

RouteStore.storeName = 'RouteStore';
RouteStore.handlers = {
    'ROUTE_CHANGED': function(state) {
        this.binding.set('state', Imm.Map(state));
    }
};

module.exports = RouteStore;