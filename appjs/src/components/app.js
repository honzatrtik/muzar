"use strict";

var React = require('react');
var Promise = require('../promise.js');

var Router = require('react-router');
var Link = Router.Link;
var Morearty = require('morearty');
var DispatcherMixin = require('../dispatcher-mixin.js');
var CategoryMenu = require('./category-menu.js');
var Navbar = require('./navbar.js');

var morearty = require('../bootstrap-morearty.js')();

var App = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    componentWillMount: function() {
        morearty.init(this);
    },

    render: function() {

        return (

            <div id="wrap">

                <Navbar />

                <div className="container">

                    <Router.RouteHandler/>

                </div>
            </div>

        );
    }
});

module.exports = App;