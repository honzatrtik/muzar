"use strict";

var Morearty = require('morearty');
var React = require('react');
var DispatcherMixin = require('../dispatcher-mixin.js');
var AdDetailStore = require('../stores/ad-detail-store.js');
var CategoryStore = require('../stores/category-store.js');
var CategoryBreadcrumbs = require('./category-breadcrumbs.js');
var Router = require('react-router');
var Link = Router.Link;

import moment from 'moment';
moment.locale('cs');


var Detail = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    renderImages(src) {
        return (
            <div key={src} className="thumbnail col-xs-12 col-sm-6 col-md-4">
                <img src={src} />
            </div>
        );
    },

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

        var path = categoryStore.getPath(ad.getIn(['category', 'str_id']));

        const src = ad.getIn(['image_urls', 0]);
        const phone = ad.getIn(['contact', 'phone']);

        return (
            <div>

                <div className="row">
                    <div className="col-xs-12 col-sm-12 col-md-12">
                        <CategoryBreadcrumbs path={path} />
                        <h1>{ad.get('name')}</h1>
                    </div>
                </div>

                <div className="row">
                    <div className="col-xs-12 col-sm-8 col-md-8">

                        <div style={{ backgroundColor: '#eee' }} className="thumbnail">
                            {src && <img width="100%" src={src + '?variant=detail'} alt={ad.get('name')} />}
                        </div>

                        <p>{ad.getIn(['description'])}</p>


                        <div className="row">
                            {ad.getIn(['image_urls']).map(this.renderImages).toJS()}
                        </div>

                    </div>



                    <div className="col-xs-12 col-sm-4 col-md-4">

                        <div className="thumbnail">

                            <h3 className="pull-left">Cena: {ad.get('price')}</h3>

                            <Link className="btn btn-primary btn-lg pull-right" to="detailReply" params={{ id: ad.get('id') }}>Kontaktovat</Link>

                            <div className="clearfix"></div>

                            <h4>Prodávající:</h4>
                            <div>
                            {ad.getIn(['user', 'username'])}<br />
                                {ad.getIn(['contact', 'district'])}<br />
                                <div className="text-muted">{ad.getIn(['contact', 'region'])}</div>
                                {phone && <div>Tel: {phone}</div>}
                            </div>

                            <p>
                                <span className="glyphicon glyphicon-time"></span> {moment(ad.get('created')).fromNow()}<br />
                                {/*<span className="glyphicon glyphicon-eye-open"></span> zobrazeno 250x*/}
                            </p>
                        </div>

                    </div>

                </div>

            </div>
        );
    }
});

module.exports = Detail;