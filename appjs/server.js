'use strict';

var debugError = require('debug')('Server error');

require('node-jsx').install({
    harmony: true
});

var _ = require('lodash');
var serverInitAction = require('./src/server-init-action.js');
var routeChangedAction = require('./src/route-changed-action.js');

var Promise = require('es6-promise').Promise;
var serializer = require('./src/serializer.js');
var React = require('react');
var Router = require('react-router');
var Morearty = require('morearty');
var Dispatcher = require('./src/bootstrap-dispatcher.js');
var HttpError = require('./src/errors/http-error.js');
var ErrorPage = require('./src/components/error-page.js');

var routes = require('./src/routes.js');

var express = require('express');
var exphbs  = require('express-handlebars');
var app = express();


function responseError(res, e) {

    debugError(e);

    if (e instanceof HttpError) {
        res.status(e.status);
    } else {
        res.status(500).end();
    }

    res.render('error', {
        html: React.renderToString(ErrorPage({
            status: e.status || 500,
            message: e.message
        })),
        title: 'Error'
    });

}

app.engine('handlebars', exphbs());
app.set('view engine', 'handlebars');


require('express-state').extend(app);

app.use('/build', express.static(__dirname + '/build'));

app.get('*', function(req, res) {

    var morearty = require('./src/bootstrap-morearty.js')(true);

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

    try {

        router.run(function(Handler, state) {

            dispatcher.executeAction(serverInitAction).then(function() {

                return dispatcher.executeAction(routeChangedAction, state).then(function() {

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

                });

            }).catch(function(e) {
                responseError(res, e);
            });



        });

    } catch(e) {
        responseError(res, e);
    }

});


var server = app.listen(3000, '127.0.0.1', function() {

    var host = server.address().address;
    var port = server.address().port;

    console.log('App listening at http://%s:%s', host, port);

});


var mockerServer = require('./mocker.js').listen(3030, '127.0.0.1', function(server) {

    var host = mockerServer.address().address;
    var port = mockerServer.address().port;

    console.log('Api listening at http://%s:%s', host, port);

});