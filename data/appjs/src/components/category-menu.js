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
import Imm from 'immutable';

var CategoryMenu = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    render: function() {

        this.observeBinding(this.getStoreBinding(CategoryStore));
        this.observeBinding(this.getStoreBinding(RouteStore));

        var store = this.getStore(CategoryStore);
        var routeStore = this.getStore(RouteStore);

        var query = routeStore.getQuery();

        var items = store.getItems() ? store.getItems() : Imm.List();
        var path = store.getActivePath().map(category => category.get('str_id'));


        var classNames = cs({
            'is-active': !path.count(),
            'main-menu-level-item': true
        });

        return (
            <nav className="main-menu">

                <ul className="main-menu-level">
                    <li className={classNames}>
                        <Link to="listAll" query={query.toJS()}>Vše</Link>
                    </li>
                </ul>

                <CategoryMenuLevel items={items} path={path} query={query} />
            </nav>
        );

    }
});

module.exports = CategoryMenu;