"use strict";

var React = require('react/addons');

var DispatcherMixin = {

    contextTypes: {
        dispatcher: React.PropTypes.object.isRequired
    },

    childContextTypes: {
        dispatcher: React.PropTypes.object.isRequired
    },

    getChildContext: function() {
        return { dispatcher: this.context.dispatcher };
    },


    getStore: function(store) {
        return this.context.dispatcher.getStore(store);
    },

    getStoreBinding: function(store) {
        return this.context.dispatcher.getStore(store).getBinding();
    }
};

module.exports = DispatcherMixin;