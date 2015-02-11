"use strict";

var React = require('react');
var Promise = require('es6-promise').Promise;

var Router = require('react-router');
var Link = Router.Link;
var Morearty = require('morearty');
var DispatcherMixin = require('../dispatcher-mixin.js');
var CategoryMenu = require('./category-menu.js');

var morearty = require('../bootstrap-morearty.js');

var App = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    componentWillMount: function() {
        morearty.init(this);
    },

    render: function() {

        return (
            <div>
                <h1>App</h1>

                <ul>
                    <li><Link to="list" params={{category: "kytary"}}>List</Link></li>
                    <li><Link to="detail" params={{id: 6}}>Detail</Link></li>
                </ul>

                <CategoryMenu />

                <Router.RouteHandler/>
            </div>

        );
    }
});

module.exports = App;