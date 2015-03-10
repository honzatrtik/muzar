"use strict";

var React = require('react');
var Promise = require('../promise.js');

var Router = require('react-router');
var Navbar = require('./navbar.js');

var morearty = require('../bootstrap-morearty.js')();

var App = React.createClass({

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