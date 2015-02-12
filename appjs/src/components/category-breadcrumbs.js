"use strict";

var _ = require('lodash');
var React = require('react');
var CategoryStore = require('../stores/category-store.js');
var AdStore = require('../stores/ad-store.js');
var Router = require('react-router');
var Link = Router.Link;

var CategoryBreadcrumbs = React.createClass({

    render() {

        var path = this.props.path || [];
        var breadcrumbs = path.map(function(part) {
            return (
                <Link key={part.strId} to="list" params={{ category: part.strId }}>{part.name}</Link>
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