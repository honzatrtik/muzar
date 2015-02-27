"use strict";

var React = require('react');
var Morearty = require('morearty');
var Router = require('react-router');
var DispatcherMixin = require('./dispatcher-mixin.js');

module.exports = function(routes, location,  cb) {

    return React.createClass({

        mixins: [Morearty.Mixin, DispatcherMixin],

        getInitialState () {
            return { Handler: null };
        },

        componentWillMount: function () {
            var self = this;
            Router.run(routes, location, function(Handler, state) {
                self.setState({ Handler: Handler }, function() {
                    self.forceUpdate();
                });
                cb(Handler, state);
            });
        },

        render: function () {
            var Handler = this.state.Handler;
            if (Handler === null) {
                return null;
            }
            var binding = this.getDefaultBinding();
            return (
                <Handler binding={binding}/>
            );
        }
    });

};