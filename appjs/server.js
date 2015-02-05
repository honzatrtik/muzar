'use strict';

require('node-jsx').install({
    harmony: true
});

var _ = require('lodash');
var routeChangedAction = require('./src/route-changed-action.js');

var serializer = require('./src/serializer.js');
var React = require('react');
var Router = require('react-router');
var Morearty = require('morearty');

var moreartyContext = Morearty.createContext({
    initialState: {},
    renderOnce: true
});

var Dispatchr = require('./src/bootstrap-dispatcher.js');
var dispatcher = new Dispatchr({
    moreartyContext: moreartyContext
});


var routes = require('./src/routes.js');

var express = require('express');
var exphbs  = require('express-handlebars');
var app = express();


app.engine('handlebars', exphbs());
app.set('view engine', 'handlebars');


require('express-state').extend(app);

app.use('/build', express.static(__dirname + '/build'));

app.get('*', function(req, res) {

    Router.run(routes, req.url, function (Handler, state) {

        routeChangedAction(dispatcher, state, function(err, routes) {

            if (err) {
                throw err;
            }

            Handler = moreartyContext.bootstrap(Handler, {
                getStore: dispatcher.dispatcherInterface.getStore
            });
            var html = React.renderToString(React.createElement(Handler));

            var serializedState = serializer.serialize(moreartyContext.getBinding().get());
            res.expose(serializedState, 'serializedState');

            var state = '<script>' + res.locals.state + '</script>';

            if (_.last(routes) === '404') {
                res.status(404);
            }

            res.render('default', {
                html: html,
                state: state,
                title: 'Some title'
            });
        });

    });

});

var server = app.listen(3000, '127.0.0.1', function() {

    var host = server.address().address;
    var port = server.address().port;

    console.log('App listening at http://%s:%s', host, port);

});