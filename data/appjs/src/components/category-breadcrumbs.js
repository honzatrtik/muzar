"use strict";

var React = require('react');
var CategoryStore = require('../stores/category-store.js');
var AdStore = require('../stores/ad-store.js');
var Router = require('react-router');
var Link = Router.Link;

var CategoryBreadcrumbs = React.createClass({

    propTypes: {
        path: React.PropTypes.array
    },

    render() {

        var path = this.props.path || [];
        var breadcrumbs = path.map(function(part) {
            return (
                <Link key={part.str_id} to="list" params={{ category: part.str_id }}>{part.name}</Link>
            );
        });

        var i = 0;
        breadcrumbs = breadcrumbs.map(function(link) {
            var key = 'separator-' + (i++);
            return [<span key={key}>&nbsp;&raquo;&nbsp;</span>, link]
        }).reduce(function(result, i) {
            return result.concat(i);
        }, []).slice(1);

        return (
            <div>{breadcrumbs}</div>
        );

    }
});

module.exports = CategoryBreadcrumbs;