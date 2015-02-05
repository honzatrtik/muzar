'use strict';

require('debug').enable('*');

var routeChangedAction = require('./src/route-changed-action.js');


var React = require('react');
var Router = require('react-router');
var Morearty = require('morearty');
var serializer = require('./src/serializer.js');

var moreartyContext = Morearty.createContext({
    initialState: serializer.unserialize(window.serializedState)
});


var Dispatchr = require('./src/bootstrap-dispatcher.js');
var dispatcher = new Dispatchr({
    moreartyContext: moreartyContext
});


var routes = require('./src/routes.js');
Router.run(routes, Router.HistoryLocation, function (Handler, state) {

    routeChangedAction(dispatcher, state, function(err) {
        if (err) {
            throw err;
        }
        Handler = moreartyContext.bootstrap(Handler, {
            getStore: dispatcher.dispatcherInterface.getStore
        });
        React.render(React.createElement(Handler), document.getElementById('content'));
    });

});

