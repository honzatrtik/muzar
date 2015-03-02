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

    getParam(name) {
        var path = ['state', 'params'];
        if (name) {
            path.push(name);
        }
        return this.binding.toJS(path);
    }

    getQuery(name) {
        var path = ['state', 'query'];
        if (name) {
            path.push(name);
        }
        return this.binding.toJS(path);
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
        this.binding.set('state', Imm.fromJS(state));
    }
};

module.exports = RouteStore;