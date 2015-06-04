"use strict";

var Promise = require('../promise.js');

module.exports = function routeChangedAction(dispatcher) {
    return dispatcher.dispatch('SERVER_INIT');
};
