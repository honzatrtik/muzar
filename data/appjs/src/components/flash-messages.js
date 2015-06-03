"use strict";

var React = require('react');
var Morearty = require('morearty');
var Router = require('react-router');
var Link = Router.Link;
var DispatcherMixin = require('../dispatcher-mixin.js');
var FlashMessagesStore = require('../stores/flash-message-store');

var FlashMessages = React.createClass({

    mixins: [Morearty.Mixin, DispatcherMixin],

    renderFlashMessage(data, key) {
        var type = data.type || 'info';
        return <div key={key} className={'alert alert-' + type}>{data.message}</div>;
    },

    render: function() {

        this.observeBinding(this.getStoreBinding(FlashMessagesStore));
        var store = this.getStore(FlashMessagesStore);

        return (
            <div>
                {store.getMessages().map(this.renderFlashMessage).toArray()}
            </div>
        );
    }
});


module.exports = FlashMessages;