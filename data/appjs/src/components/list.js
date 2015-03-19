"use strict";

var React = require('react');
var Morearty = require('morearty');
var DispatcherMixin = require('../dispatcher-mixin.js');
var CategoryStore = require('../stores/category-store.js');
var RouteStore = require('../stores/route-store.js');
var AdStore = require('../stores/ad-store.js');
var Router = require('react-router');
var Link = Router.Link;
var AdPreview = require('./ad-preview.js');
var CategoryMenu = require('./category-menu.js');
var CategoryBreadcrumbs = require('./category-breadcrumbs.js');
var adLoadNextAction = require('../actions/ad-load-next-action.js');
var cs = React.addons.classSet;
var ListFilters = require('./list-filters');

var List = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],


    handleLoadNextButtonClick: function() {
        this.executeAction(adLoadNextAction);
    },

    renderEmptyMessage() {
        var adStore = this.getStore(AdStore);
        var items = adStore.getItems();
        if (!items.size && !adStore.isLoading()) {
            return (
                <div>Nejsou tu žádné inzeráty</div>
            );
        } else {
            return null;
        }
    },

    renderFooter: function() {
        var adStore = this.getStore(AdStore);
        var items = adStore.getItems();
        var hasNext = adStore.hasNextLink();

        if (hasNext) {
            var disabled = adStore.isLoading();
            var classNames = cs({
                'btn': true,
                'btn-primary': true,
                'btn-lg': true,
                'btn-disabled': disabled
            });
            return (
                <div className="row">
                    <div className="text-center col-xs-12 col-sm-12 col-md-12">
                        <button onClick={this.handleLoadNextButtonClick} disabled={disabled} type="submit" className={classNames}>Zobrazit další inzeráty</button>
                        <div className="pull-right">{items.size}/{adStore.getTotal()}</div>
                    </div>
                </div>
            );
        } else {
            return null;
        }
    },

    renderItem: function(item) {
        return <AdPreview key={item.get('id')} item={item} />
    },


    render: function() {

        this.observeBinding(this.getStoreBinding(RouteStore));
        this.observeBinding(this.getStoreBinding(AdStore));
        this.observeBinding(this.getStoreBinding(CategoryStore));

        var categoryStore = this.getStore(CategoryStore);
        var adStore = this.getStore(AdStore);

        var path = categoryStore.getActivePath();
        var title = path.length ? path[path.length-1].name : 'Všechny inzeráty';

        var items = adStore.getItems().map(this.renderItem);

        return (

            <div className="row">

                <div id="sidebar" className="hidden-xs hidden-sm col-md-3">
                    <CategoryMenu />
                </div>

                <div className="col-xs-12 col-sm-12 col-md-9">

                    <ListFilters />

                    <h1>{title}{adStore.isLoading() ? <small> Loading...</small> : null}</h1>
                    {path ? <div><CategoryBreadcrumbs path={path}/></div> : null}
                    {items.toArray()}
                    {this.renderEmptyMessage()}
                    {this.renderFooter()}
                </div>


            </div>
        );
    }
});

module.exports = List;