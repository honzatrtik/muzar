"use strict";

var React = require('react');

var Router = require('react-router');
var Link = Router.Link;
var Morearty = require('morearty');
var DispatcherMixin = require('../dispatcher-mixin.js');


var App = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    render() {

        return (
            <div>
                <h1>App</h1>
                <ul>
                    <li><Link to="list">List</Link></li>
                    <li><Link to="detail" params={{id: 6}}>Detail</Link></li>
                </ul>

                <Router.RouteHandler/>
            </div>

        );
    }
});

module.exports = App;