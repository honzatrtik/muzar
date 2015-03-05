"use strict";

var Promise = require('../promise.js');
var AdStore = require('../stores/ad-store.js');

module.exports = function adLoadNextAction(dispatcher) {

    if (dispatcher.getStore(AdStore).hasNextLink()) {
        return dispatcher.dispatch('AD_LOAD_NEXT');
    }

};
