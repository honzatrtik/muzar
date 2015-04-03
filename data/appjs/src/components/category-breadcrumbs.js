"use strict";

var React = require('react');
var CategoryStore = require('../stores/category-store.js');
var AdStore = require('../stores/ad-store.js');
var Router = require('react-router');
var Link = Router.Link;
import Imm from 'immutable';

var CategoryBreadcrumbs = React.createClass({

    propTypes: {
        path: React.PropTypes.instanceOf(Imm.List)
    },

    render() {

        var path = this.props.path || Imm.List();
        var breadcrumbs = path.map(function(part) {
            return (
                <Link key={part.get('str_id')} to="list" params={{ category: part.get('str_id') }}>{part.get('name')}</Link>
            );
        }).interpose(' / ');

        return (
            <div>{breadcrumbs.toArray()}</div>
        );

    }
});

module.exports = CategoryBreadcrumbs;