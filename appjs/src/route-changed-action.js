"use strict";

var _ = require('lodash');
var Promise = require('es6-promise').Promise;

module.exports = function routeChangedAction(context, state, done) {

    var routes = _.pluck(state.routes, 'name');
    state = _.omit(state, 'routes');
    state.routes = routes;

    context.dispatch('ROUTE_CHANGED', state);
    done(null, routes);

};
