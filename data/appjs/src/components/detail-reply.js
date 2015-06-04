"use strict";

var Morearty = require('morearty');
var React = require('react');
var DispatcherMixin = require('../dispatcher-mixin.js');
var AdDetailStore = require('../stores/ad-detail-store.js');
var RouteStore = require('../stores/route-store');
var CategoryStore = require('../stores/category-store.js');
var CategoryBreadcrumbs = require('./category-breadcrumbs.js');
var Router = require('react-router');
var Link = Router.Link;


import ReplyForm from './reply-form.js';
import ReplyFormStore from '../stores/reply-form-store.js';
import AdPreview from './ad-preview.js';

import moment from 'moment';
moment.locale('cs');


var DetailReply = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],


    render() {

        this.observeBinding(this.getStoreBinding(AdDetailStore));
        this.observeBinding(this.getStoreBinding(ReplyFormStore));

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

        return (
            <div>

                <div className="row">
                    <div className="col-xs-12 col-sm-12 col-md-12">
                        <CategoryBreadcrumbs path={path} />
                        <h1>Opověď na inzerát: <Link to="detail" params={{ id: ad.get('id') }}>{ad.get('name')}</Link></h1>
                    </div>
                </div>

                <div className="row">
                    <div className="col-xs-12 col-sm-6 col-md-6">
                        <ReplyForm id={ad.get('id')} />
                    </div>
                    <div className="hidden-xs hidden-sm col-md-4">
                        <AdPreview item={ad} />
                    </div>
                </div>

            </div>
        );
    }
});

module.exports = DetailReply;