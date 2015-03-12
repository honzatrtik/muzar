"use strict";

var Morearty = require('morearty');
var React = require('react/addons');
var Router = require('react-router');
var Link = Router.Link;
var DispatcherMixin = require('../dispatcher-mixin.js');
var CategoryStore = require('../stores/category-store.js');
var RouteStore = require('../stores/route-store.js');
var CategoryMenuLevel = require('./category-menu-level.js');
var cs = React.addons.classSet;

var CategoryMenu = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    render: function() {

        this.observeBinding(this.getStoreBinding(CategoryStore));
        this.observeBinding(this.getStoreBinding(RouteStore));

        var store = this.getStore(CategoryStore);
        var routeStore = this.getStore(RouteStore);

        var query = routeStore.getQuery().toJS();

        var items = store.getItems() ? store.getItems().toJS() : [];
        var path = store.getActivePath().map(category => category.str_id);

        var classNames = cs({
            'is-active': !path.length,
            'mainMenu-level-item': true
        });

        return (
            <nav className="mainMenu">

                <ul className="mainMenu-level">
                    <li className={classNames}>
                        <Link to="listAll" query={query}>Vše</Link>
                    </li>
                </ul>

                <CategoryMenuLevel items={items} path={path} query={query} />
            </nav>
        );

    }
});

module.exports = CategoryMenu;