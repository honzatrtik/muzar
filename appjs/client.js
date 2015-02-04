'use strict';

var routeChangedAction = require('./src/route-changed-action.js');

var React = require('react');
var Router = require('react-router');
var Morearty = require('morearty');

var moreartyContext = Morearty.createContext({
    initialState: window.serializedState
});

var Dispatchr = require('dispatchr')();
var RouteStore = require('./src/stores/route-store.js');
var AdStore = require('./src/stores/ad-store.js');

Dispatchr.registerStore(RouteStore);
Dispatchr.registerStore(AdStore);

var dispatcher = new Dispatchr({
    moreartyContext: moreartyContext
});


var routes = require('./src/routes.js');
Router.run(routes, Router.HistoryLocation, function (Handler, state) {

    routeChangedAction(dispatcher, state, function(err) {
        if (err) {
            throw err;
        }
        React.render(React.createElement(Handler), document.getElementById('content'));
    });

});

