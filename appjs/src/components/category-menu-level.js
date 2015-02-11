"use strict";

var _ = require('lodash');
var Morearty = require('morearty');
var React = require('react/addons');
var Router = require('react-router');
var Link = Router.Link;


var CategoryMenuLevel = React.createClass({

    mixins: [Morearty.Mixin],

    render: function() {

        var cs = React.addons.classSet;

        var path = this.props.path || [];

        var items = _.map(this.props.items, function(item) {

            var active = item.strId === path[0];
            var classNames = cs({
                active: active
            });

            return (
                <li className={classNames} key={item.strId}>
                    <Link to="list" params={{category: item.strId}}>{item.name}</Link>
                    {item.children ? <CategoryMenuLevel items={item.children} path={active ? path.slice(1) : []}/> : null}
                </li>
            );
        });

        return (
            <ul>{items}</ul>
        );

    }
});

module.exports = CategoryMenuLevel;