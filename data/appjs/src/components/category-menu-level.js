"use strict";

var Morearty = require('morearty');
var React = require('react/addons');
var Router = require('react-router');
var Link = Router.Link;
var CategoryStore = require('../stores/category-store.js');
var ReactCSSTransitionGroup = React.addons.CSSTransitionGroup;
import Imm from 'immutable';

var CategoryMenuLevel = React.createClass({

    propTypes: {
        path: React.PropTypes.array,
        items: React.PropTypes.instanceOf(Imm.List).isRequired,
        query: React.PropTypes.object
    },

    render: function() {

        var cs = React.addons.classSet;
        var path = this.props.path || [];
        var self = this;

        var query = self.props.query || {};

        var items = this.props.items.map(function(item) {

            var active = item.get('str_id') === path[0];
            var classNames = cs({
                'is-active': active,
                'mainMenu-level-item': true
            });

            var children = item.get('children');
            var expanded = (self.props.expanded || active) && children && children.size;

            return (
                <li className={classNames} key={item.get('str_id')}>
                    <Link to="list" params={{category: item.get('str_id')}} query={query}>{item.get('name')}</Link>
                    <ReactCSSTransitionGroup component="div" transitionLeave={false} transitionName="mainMenu">
                        {expanded ? <CategoryMenuLevel items={item.get('children')} path={active ? path.slice(1) : []} query={query}/> : null}
                    </ReactCSSTransitionGroup>
                </li>
            );
        });

        return (
            <ul className="mainMenu-level">{items.toArray()}</ul>
        );

    }
});

module.exports = CategoryMenuLevel;