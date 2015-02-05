"use strict";


var debug = require('debug')('Test');
var Morearty = require('morearty');
var React = require('react');

var Test = React.createClass({

    render() {

        debug(this.context);
        return <div>TEstik!</div>;
    }
});

module.exports = Test;