"use strict";

var React = require('react');

var Router = require('react-router');
var Link = Router.Link;

var App = React.createClass({
    render() {
        return (

            <div>
                <h1>App</h1>
                <ul>
                    <li><Link to="list">List</Link></li>
                    <li><Link to="detail" params={{id: 6}}>Detail</Link></li>
                </ul>

                <Router.RouteHandler />
            </div>

        );
    }
});

module.exports = App;