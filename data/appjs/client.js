'use strict';

require('es6-shim');

var debugError = require('debug')('Client error');
var debugBinding = require('debug')('Binding');

require('debug').enable('*');

var routeChangedAction = require('./src/actions/route-changed-action.js');

var React = require('react');
var Router = require('react-router');
var serializer = require('./src/serializer.js');
var morearty = require('./src/bootstrap-morearty.js')();

var Dispatchr = require('./src/bootstrap-dispatcher.js');
var dispatcher = new Dispatchr({
    morearty: morearty
});


var routes = require('./src/routes.js');
var context = {
    dispatcher: dispatcher,
    morearty: morearty
};

var router = Router.create({
    routes: routes,
    location: Router.HistoryLocation
});

router.run(function(Handler, state) {

    React.withContext(context, function () {
        return React.render(React.createElement(Handler), document.getElementById('content'));
    });

    dispatcher.executeAction(routeChangedAction, state).catch(function(e) {
        debugError(e);
    });

});



