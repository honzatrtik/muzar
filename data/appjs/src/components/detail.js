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
            return (
                <div className="row">
                    <div className="col-xs-12 col-sm-12 col-md-12">
                        <h1 className="pull-left"><small> Loading...</small></h1>
                    </div>
                </div>
            );
        }

        ad = ad.toJS();
        var path = categoryStore.getPath(ad.category.str_id);

        return (
            <div>

                <div className="row">
                    <div className="col-xs-12 col-sm-12 col-md-12">
                        <CategoryBreadcrumbs path={path} />
                        <h1>{ad.name}</h1>
                    </div>
                </div>

                <div className="row">
                    <div className="col-xs-12 col-sm-8 col-md-8">

                        <div className="thumbnail">
                            <img width="100%" src={ad.image_url} alt={ad.name} />
                        </div>
                    </div>



                    <div className="col-xs-12 col-sm-4 col-md-4">

                        <div className="thumbnail">

                            <h3 className="pull-left">Cena: {ad.price}</h3>

                            <a href="" className="btn btn-primary btn-lg pull-right">Kontaktovat</a>

                            <div className="clearfix"></div>

                            <h4>Prodávající:</h4>
                            <p>
                            {ad.user && ad.user.username}<br />
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