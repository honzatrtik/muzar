"use strict";

var React = require('react');

var ErrorPage = React.createClass({
    render() {

        var status = this.props.status || 404;
        var message = this.props.message || 'Not found';

        return <div><h2>{status}: <em>{message}</em></h2></div>;
    }
});

module.exports = ErrorPage;