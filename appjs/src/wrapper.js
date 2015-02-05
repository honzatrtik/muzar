"use strict";

var React = require('react');
var Morearty = require('morearty');
var Router = require('react-router');
var routes = require('./routes.js');

var Wrapper = React.createClass({

    mixins: [Morearty.Mixin],

    getInitialState () {
        return { Handler: null };
    },

    componentDidMount: function () {
        Router.run(routes, function(Handler, state) {
            this.setState({ Handler: Handler }, function() {
                this.forceUpdate(); // morearty doesn't let this happen I guess
            });
        });
    },

    render: function () {
        var Handler = this.state.Handler;
        if (Handler === null)
            return null;
        var binding = this.getDefaultBinding();
        return (
            <Handler binding={binding}/>
        );
    }
});

module.exports = Wrapper;