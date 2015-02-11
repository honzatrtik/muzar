"use strict";

var Morearty = require('morearty');
var React = require('react');
var DispatcherMixin = require('../dispatcher-mixin.js');
var AdDetailStore = require('../stores/ad-detail-store.js');

var Test = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    render() {

        this.observeBinding(this.getStoreBinding(AdDetailStore));

        var store = this.getStore(AdDetailStore);
        var ad = store.getAd();
        ad = ad ? ad.toJS() : {};

        return (
            <div>
                <h2>Nazev: {ad.name}</h2>
                <p>
                    {ad.district}
                </p>
            </div>
        );
    }
});

module.exports = Test;