"use strict";

var Promise = require('es6-promise').Promise;

module.exports = function routeChangedAction(context, state, done) {

    context.dispatch('ROUTE_CHANGED', state);
    done();

};
