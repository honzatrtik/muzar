"use strict";

var React = require('react');
var Router = require('react-router');
var Route = Router.Route;
var DefaultRoute = Router.DefaultRoute;
var NotFoundRoute = Router.NotFoundRoute;
var Link = Router.Link;
var RouteHandler = Router.RouteHandler;

var App = require('./components/app.js');
var List = require('./components/list.js');
var Test = require('./components/test.js');
var NotFound = require('./components/not-found.js');

module.exports = (
    <Route name="app" path="/" handler={App}>
        <Route name="detail" path="detail/:id" handler={Test}/>
        <Route name="list" path="list/:category" handler={List}/>
        <NotFoundRoute name="404" handler={NotFound} />
    </Route>
);
