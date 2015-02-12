"use strict";

var React = require('react');
var Promise = require('es6-promise').Promise;

var Router = require('react-router');
var Link = Router.Link;
var Morearty = require('morearty');
var DispatcherMixin = require('../dispatcher-mixin.js');
var CategoryMenu = require('./category-menu.js');

var morearty = require('../bootstrap-morearty.js')();

var App = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    componentWillMount: function() {
        morearty.init(this);
    },

    render: function() {

        return (
            <div className="container">

               <div className="row">
                   <h1>Muzar</h1>
               </div>

                <Router.RouteHandler/>

            </div>

        );
    }
});

module.exports = App;