'use strict';

require('debug').enable('*');

var routeChangedAction = require('./src/route-changed-action.js');

var React = require('react');
var Router = require('react-router');
var Morearty = require('morearty');
var serializer = require('./src/serializer.js');

var morearty = Morearty.createContext({
    initialState: serializer.unserialize(window.serializedState)
});


var Dispatchr = require('./src/bootstrap-dispatcher.js');
var dispatcher = new Dispatchr({
    morearty: morearty
});

var routes = require('./src/routes.js');

var Wrapper = require('./src/wrapper.js')(routes, Router.HistoryLocation, function(Handler, state) {
    routeChangedAction(dispatcher, state);
});

React.withContext({ dispatcher: dispatcher }, function () {
    Wrapper = morearty.bootstrap(Wrapper);
    React.render(React.createElement(Wrapper), document.getElementById('content'));
});

