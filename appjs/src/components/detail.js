"use strict";

var Morearty = require('morearty');
var React = require('react');
var DispatcherMixin = require('../dispatcher-mixin.js');
var AdDetailStore = require('../stores/ad-detail-store.js');
var CategoryStore = require('../stores/category-store.js');
var CategoryBreadcrumbs = require('./category-breadcrumbs.js');

var Detail = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    render() {

        this.observeBinding(this.getStoreBinding(AdDetailStore));

        var adStore = this.getStore(AdDetailStore);
        var categoryStore = this.getStore(CategoryStore);

        var ad = adStore.getAd();

        if (!ad) {
            return <div>Loading</div>;
        }

        ad = ad.toJS();
        var path = categoryStore.getPath(ad.categories[0].strId);

        return (
            <div>

                <div className="row">
                    <div className="col-xs-12 col-sm-12 col-md-12">
                        <CategoryBreadcrumbs path={path} />
                        <h2 className="pull-left">{ad.name}</h2>
                    </div>
                </div>

                <div className="row">
                    <div className="col-xs-12 col-sm-8 col-md-8">

                        <div className="thumbnail">
                            <img src="http://lorempixel.com/g/640/480/cats/" alt="Cat" />
                        </div>
                    </div>



                    <div className="col-xs-12 col-sm-4 col-md-4">

                        <div className="thumbnail">

                            <h3 className="pull-left">Cena: {ad.price}</h3>

                            <a href="" className="btn btn-primary btn-lg pull-right">Kontaktovat</a>

                            <div className="clearfix"></div>

                            <h4>Prodávající:</h4>
                            <p>
                                Pavel Novák<br />
                                Třinec<br />
                                <span className="text-muted">Ostravsky kraj</span><br />
                                Tel: 736252366<br />
                            </p>

                            <p>
                                <span className="glyphicon glyphicon-time"></span> před 5 hodinami<br />
                                <span className="glyphicon glyphicon-eye-open"></span> zobrazeno 250x
                            </p>
                        </div>

                    </div>

                </div>

            </div>
        );
    }
});

module.exports = Detail;