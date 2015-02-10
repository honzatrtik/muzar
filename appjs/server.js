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
var Dispatchr = require('./src/bootstrap-dispatcher.js');


var routes = require('./src/routes.js');

var express = require('express');
var exphbs  = require('express-handlebars');
var app = express();


app.engine('handlebars', exphbs());
app.set('view engine', 'handlebars');


require('express-state').extend(app);

app.use('/build', express.static(__dirname + '/build'));

app.get('*', function(req, res) {

    var morearty = Morearty.createContext({
        initialState: {},
        renderOnce: true
    });

    var dispatcher = new Dispatchr({
        morearty: morearty
    });

    var Wrapper = require('./src/wrapper.js')(routes, req.url, function(Handler, state) {
        routeChangedAction(dispatcher, state);
    });

    var html = React.withContext({ dispatcher: dispatcher }, function () {
        Wrapper = morearty.bootstrap(Wrapper);
        return React.renderToString(React.createElement(Wrapper));
    });

    var serializedState = serializer.serialize(morearty.getBinding().get());
    res.expose(serializedState, 'serializedState');

    var state = '<script>' + res.locals.state + '</script>';

    res.render('default', {
        html: html,
        state: state,
        title: 'Some title'
    });

});

var server = app.listen(3000, '127.0.0.1', function() {

    var host = server.address().address;
    var port = server.address().port;

    console.log('App listening at http://%s:%s', host, port);

});