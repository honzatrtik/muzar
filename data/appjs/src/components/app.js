"use strict";

var React = require('react');
var Promise = require('../promise.js');

var Router = require('react-router');
var Navbar = require('./navbar.js');
var FlashMessages = require('./flash-messages.js');
var ScrollToTopLink = require('./scroll-to-top-link.js');

var morearty = require('../bootstrap-morearty.js')();


import DocumentTitle from 'react-document-title';

var App = React.createClass({

    componentWillMount: function() {
        morearty.init(this);
    },

    render: function() {

        return (

            <DocumentTitle title="Muzar.cz">
                <div id="wrap">

                    <Navbar />

                    <FlashMessages />

                    <div className="container">

                        <Router.RouteHandler/>

                    </div>

                    <ScrollToTopLink />
                </div>
            </DocumentTitle>

        );
    }
});

module.exports = App;