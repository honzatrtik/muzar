"use strict";

var React = require('react');
var Morearty = require('morearty');
var DispatcherMixin = require('../dispatcher-mixin.js');
var CategoryStore = require('../stores/category-store.js');
var AdStore = require('../stores/ad-store.js');
var RouteStore = require('../stores/route-store.js');
var Router = require('react-router');
var Link = Router.Link;
var AdPreview = require('./ad-preview.js');
var cs = React.addons.classSet;


var Search = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    renderItem: function(item) {
        item = item.toJS();
        return <AdPreview key={item.id} item={item} />
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

    renderFilters() {
        return (
            <div className="col-xs-12 col-sm-12 col-md-12">
                <form className="form-inline" role="form">
                    V okoli
                    <div className="btn-group">
                        <button type="button" className="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            Ceska Republika <span className="caret" />
                        </button>
                        <ul className="dropdown-menu" role="menu">
                            <input type="text" className="form-control" />
                            <li><a href="#">Basove nastroje</a></li>
                            <li><a href="#">Bici</a></li>
                        </ul>
                    </div>
                    V cene
                    <div className="btn-group">
                        <button type="button" className="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            nerozhoduje <span className="caret" />
                        </button>
                        <ul className="dropdown-menu" role="menu">
                            <li><a href="#">Od</a></li>
                            <li><a href="#">Do</a></li>
                        </ul>
                    </div>
                </form>
            </div>
        );
    },


    renderWatchdog() {
        return (
            <div className="col-xs-12 col-sm-12 col-md-12 well">
                <form className="form-inline" role="form">
                    Chceš poslat upozornění na email, když se objeví nový inzerát odpovidajici tomuto filtru?
                    <button type="submit" className="btn btn-primary pull-right"><i className="glyphicon glyphicon-envelope" /> Upozornit mě emailem</button>
                </form>
            </div>
        );
    },

    renderResultCount() {
        var adStore = this.getStore(AdStore);
        return (
            <div className="col-xs-12 col-sm-12 col-md-12">
                <p>
                    Našli jsme ti <strong>{adStore.getTotal()} inzerátů</strong>
                </p>
            </div>
        );
    },

    render() {

        this.observeBinding(this.getStoreBinding(AdStore));
        var routeStore = this.getStore(RouteStore);
        var adStore = this.getStore(AdStore);

        var title = routeStore.getQuery('query');
        var items = adStore.getItems().map(this.renderItem);

        if (adStore.isLoading()) {
            return (
                <div className="row">
                    <div className="col-xs-12 col-sm-12 col-md-12">
                        <h1 className="pull-left">
                            Hledá se:{' '}<i>"{title}"</i>
                            <small> Loading...</small>
                        </h1>
                    </div>
                </div>
            );
        }

        return (

            <div className="row">
                <div className="col-xs-12 col-sm-12 col-md-12">
                    <div className="col-xs-12 col-sm-12 col-md-12">
                        <h1>Výsledky hledání{' '}<i>"{title}"</i></h1>
                    </div>


                    {this.renderFilters()}
                    {this.renderWatchdog()}
                    {this.renderResultCount()}




                    <div className="col-xs-12 col-sm-12 col-md-12">
                        <div className="row">

                            <div className="col-xs-12 col-sm-12 col-md-12">
                                {items.toJS()}
                                {this.renderEmptyMessage()}
                                {this.renderFooter()}
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        );
    }
});

module.exports = Search;