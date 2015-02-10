"use strict";

var _ = require('lodash');
var Promise = require('es6-promise').Promise;

module.exports = function routeChangedAction(dispatcher, state) {

    var routes = _.pluck(state.routes, 'name');
    state = _.omit(state, 'routes');
    state.routes = routes;

    return dispatcher.dispatch('ROUTE_CHANGED', state);
};
