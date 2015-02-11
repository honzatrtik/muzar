"use strict";

var _ = require('lodash');
var Promise = require('es6-promise').Promise;

module.exports = function routeChangedAction(dispatcher) {
    return dispatcher.dispatch('SERVER_INIT');
};
