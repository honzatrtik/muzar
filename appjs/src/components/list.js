"use strict";

var _ = require('lodash');
var React = require('react');
var Morearty = require('morearty');
var DispatcherMixin = require('../dispatcher-mixin.js');
var CategoryStore = require('../stores/category-store.js');
var AdStore = require('../stores/ad-store.js');
var Router = require('react-router');
var Link = Router.Link;
var AdPreview = require('./ad-preview.js');
var CategoryMenu = require('./category-menu.js');
var CategoryBreadcrumbs = require('./category-breadcrumbs.js');

var List = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    render() {

        this.observeBinding(this.getStoreBinding(AdStore));
        this.observeBinding(this.getStoreBinding(CategoryStore));

        var categoryStore = this.getStore(CategoryStore);
        var adStore = this.getStore(AdStore);

        var path = categoryStore.getActivePath();
        var title = _.last(path).name;

        var items = adStore.getItems().map(function(item) {
            item = item.toJS();
            return <AdPreview key={item.id} item={item} />
        });

        return (

            <div className="row">

                <div id="sidebar" className="hidden-xs hidden-sm col-md-3">
                    <CategoryMenu />
                </div>

                <div className="col-xs-12 col-sm-12 col-md-9">
                    <h2>{title}</h2>
                    <div><CategoryBreadcrumbs path={path}/></div>
                    {items.toJS()}
                </div>

            </div>
        );
    }
});

module.exports = List;