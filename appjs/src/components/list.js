"use strict";

var React = require('react');
var Morearty = require('morearty');
var DispatcherMixin = require('../dispatcher-mixin.js');
var CategoryStore = require('../stores/category-store.js');


var List = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    render() {

        this.observeBinding(this.getStoreBinding(CategoryStore));
        var store = this.getStore(CategoryStore);


        return (
            <div>
                <span>list:</span>
                <h1>{store.getActive()}</h1>
            </div>
        );
    }
});

module.exports = List;