"use strict";

var React = require('react');
var Router = require('react-router');
var Route = Router.Route;
var DefaultRoute = Router.DefaultRoute;
var NotFoundRoute = Router.NotFoundRoute;
var Redirect = Router.Redirect;
var Link = Router.Link;
var RouteHandler = Router.RouteHandler;

var App = require('./components/app.js');
var List = require('./components/list.js');
var Login = require('./components/login.js');
var Search = require('./components/search.js');
var Detail = require('./components/detail.js');
var DetailReply = require('./components/detail-reply.js');
var Create = require('./components/create.js');
var CreateSuccess = require('./components/create-success.js');
var ErrorPage = require('./components/error-page.js');

module.exports = (
    <Route name="home" path="/" handler={App}>
        <Route name="detail" path="detail/:id" handler={Detail} />
        <Route name="detailReply" path="detail/:id/replies" handler={DetailReply}/>
        <Route name="listAll" path="list" handler={List}/>
        <Route name="list" path="list/:category" handler={List}/>
        <Route name="search" path="search" handler={Search}/>
        <Route name="create" path="create" handler={Create}/>
        <Route name="createSuccess" path="create-success" handler={CreateSuccess}/>
        <Route name="login" path="login" handler={Login}/>
        <Redirect from="/" to="listAll" />
        <NotFoundRoute name="404" handler={ErrorPage} />
    </Route>
);
