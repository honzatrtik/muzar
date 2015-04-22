"use strict";

var Promise = require('../promise.js');
var RouteStore = require('../stores/route-store.js');
var Imm = require('immutable');

import Path from 'react-router/modules/utils/Path.js';

module.exports = function routeChangedAction(dispatcher, state) {


    state = Imm.fromJS(state);
    state = state.update('routes', routes => routes.map(route => route.get('name'))).toJS();
    state.path = Path.decode(state.path);



    // Dispatch only if path differs - prevent initial dispatch on client
    if (dispatcher.getStore(RouteStore).getPath() !== state.path) {
        return dispatcher.dispatch('ROUTE_CHANGED', state);
    }

};
