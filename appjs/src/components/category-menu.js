"use strict";

var _ = require('lodash');
var Morearty = require('morearty');
var React = require('react/addons');
var Router = require('react-router');
var Link = Router.Link;
var DispatcherMixin = require('../dispatcher-mixin.js');
var CategoryStore = require('../stores/category-store.js');
var CategoryMenuLevel = require('./category-menu-level.js');
var cs = React.addons.classSet;

var CategoryMenu = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    render: function() {

        this.observeBinding(this.getStoreBinding(CategoryStore));
        var store = this.getStore(CategoryStore);

        var items = store.getItems() ? store.getItems().toJS() : [];
        var path = _.pluck(store.getActivePath(), 'strId');

        var classNames = cs({
            'is-active': !path.length,
            'mainMenu-level-item': true
        });

        return (
            <nav className="mainMenu">

                <ul className="mainMenu-level">
                    <li className={classNames}>
                        <Link to="listAll">Vše</Link>
                    </li>
                </ul>

                <CategoryMenuLevel items={items} path={path} />
            </nav>
        );

    }
});

module.exports = CategoryMenu;