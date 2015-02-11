'use strict';

var debugError = require('debug')('Server error');

require('node-jsx').install({
    harmony: true
});

var _ = require('lodash');
var serverInitAction = require('./src/server-init-action.js');
var routeChangedAction = require('./src/route-changed-action.js');

var debug = require('debug')('Binding');
var Promise = require('es6-promise').Promise;
var serializer = require('./src/serializer.js');
var React = require('react');
var Router = require('react-router');
var Morearty = require('morearty');
var Dispatcher = require('./src/bootstrap-dispatcher.js');
var HttpError = require('./src/errors/http-error.js');

var routes = require('./src/routes.js');

var express = require('express');
var exphbs  = require('express-handlebars');
var app = express();


app.engine('handlebars', exphbs());
app.set('view engine', 'handlebars');


require('express-state').extend(app);

app.use('/build', express.static(__dirname + '/build'));

app.get('*', function(req, res) {

    var morearty = require('./src/bootstrap-morearty.js');

    var dispatcher = new Dispatcher({
        morearty: morearty
    });

    var context = {
        dispatcher: dispatcher,
        morearty: morearty
    };

    var router = Router.create({
        routes: routes,
        location: req.url
    });

    router.run(function(Handler, state) {

        var promises = [];

        promises.push(serverInitAction(dispatcher));
        promises.push(routeChangedAction(dispatcher, state));

        Promise.all(promises).then(function() {

            var html = React.withContext(context, function () {
                Handler = morearty.bootstrap(Handler, context);
                return React.renderToString(React.createElement(Handler));
            });

            var serializedState = serializer.serialize(morearty.getBinding().get());
            res.expose(serializedState, 'serializedState');

            if (_.find(state.routes, { 'name': '404' })) {
                res.status(404);
            }

            res.render('default', {
                html: html,
                state: '<script>' + res.locals.state + '</script>',
                title: 'Some title'
            });

        }).catch(function(e) {

            debugError(e);
            if (e instanceof HttpError) {
                res.status(e.status).send(e.message);
            } else {
                res.status(500).end();
            }
        });

    });







});

var server = app.listen(3000, '127.0.0.1', function() {

    var host = server.address().address;
    var port = server.address().port;

    console.log('App listening at http://%s:%s', host, port);

});