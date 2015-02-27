"use strict";

var _ = require('lodash');
var Promise = require('./promise.js');

module.exports = function routeChangedAction(dispatcher) {
    return dispatcher.dispatch('SERVER_INIT');
};
