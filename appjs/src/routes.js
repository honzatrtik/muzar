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
var Detail = require('./components/detail.js');
var Create = require('./components/create.js');
var ErrorPage = require('./components/error-page.js');

module.exports = (
    <Route name="app" path="/" handler={App}>
        <Route name="detail" path="detail/:id" handler={Detail}/>
        <Route name="list" path="list/:category" handler={List}/>
        <Route name="create" path="create" handler={Create}/>
        <NotFoundRoute name="404" handler={ErrorPage} />
    </Route>
);
