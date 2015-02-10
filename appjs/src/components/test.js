"use strict";

var Morearty = require('morearty');
var React = require('react');
var DispatcherMixin = require('../dispatcher-mixin.js');
var AdDetailStore = require('../stores/ad-detail-store.js');

var Test = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    render() {

        var store = this.getStore(AdDetailStore);
        this.observeBinding(this.getStoreBinding(AdDetailStore));

        return <div>TEstik!</div>;
    }
});

module.exports = Test;