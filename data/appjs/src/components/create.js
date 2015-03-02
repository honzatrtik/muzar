"use strict";

var Morearty = require('morearty');
var React = require('react');
var DispatcherMixin = require('../dispatcher-mixin.js');
var AdForm = require('./ad-form.js');

var Create = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    render() {

        return (
            <AdForm />
        );
    }
});

module.exports = Create;