"use strict";

var _ = require('lodash');
var Promise = require('./promise.js');
var RouteStore = require('./stores/route-store.js');

module.exports = function routeChangedAction(dispatcher, state) {

    var routes = _.pluck(state.routes, 'name');
    state = _.omit(state, 'routes');
    state.routes = routes;

    // Dispatch only if path differs - prevent initial dispatch on client
    if (dispatcher.getStore(RouteStore).getPath() !== state.path) {
        return dispatcher.dispatch('ROUTE_CHANGED', state);
    }

};
