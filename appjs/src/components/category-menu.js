"use strict";

var _ = require('lodash');
var Morearty = require('morearty');
var React = require('react/addons');
var DispatcherMixin = require('../dispatcher-mixin.js');
var CategoryStore = require('../stores/category-store.js');
var CategoryMenuLevel = require('./category-menu-level.js');

var CategoryMenu = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    render: function() {

        this.observeBinding(this.getStoreBinding(CategoryStore));
        var store = this.getStore(CategoryStore);


        var items = store.getItems() ? store.getItems().toJS() : [];
        return (
            <nav>
                <CategoryMenuLevel items={items} />
            </nav>
        );

    }
});

module.exports = CategoryMenu;