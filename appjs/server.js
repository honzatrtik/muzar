'use strict';

require('node-jsx').install({
    harmony: true
});

var routeChangedAction = require('./src/route-changed-action.js');

var serializer = require('./src/serializer.js');
var React = require('react');
var Router = require('react-router');
var Morearty = require('morearty');

var moreartyContext = Morearty.createContext({
    initialState: {
    }
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

var express = require('express');
var exphbs  = require('express-handlebars');
var app = express();

app.engine('handlebars', exphbs());
app.set('view engine', 'handlebars');

require('express-state').extend(app);

app.use('/build', express.static(__dirname + '/build'));
app.use(function (req, res) {

    Router.run(routes, req.url, function (Handler, state) {

        routeChangedAction(dispatcher, state, function(err) {
            if (err) {
                throw err;
            }

            var html = React.renderToString(React.createElement(Handler));

            //console.log(moreartyContext.getBinding().get().toJS());
            //var serializedState = serializer.serialize(moreartyContext.getBinding().get());
            res.expose(moreartyContext.getBinding().get().toJS(), 'serializedState');

            var state = '<script>' + res.locals.state + '</script>';

            res.render('default', {
                html: html,
                state: state,
                title: 'Some title'
            });
        });

    });

});

var server = app.listen(3000, function () {

    var host = server.address().address;
    var port = server.address().port;

    console.log('App listening at http://%s:%s', host, port);

});