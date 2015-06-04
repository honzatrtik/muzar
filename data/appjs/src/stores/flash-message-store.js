"use strict";

var BaseStore = require('./base-store.js');
var CategoryStore = require('./category-store.js');
var RouteStore = require('./route-store.js');
var Promise = require('../promise.js');
var superagent = require('../superagent.js');
var HttpError = require('../errors/http-error.js');
var Imm = require('immutable');
var urlParse = require('url-parse');



class FlashMessageStore extends BaseStore {

    constructor(dispatcher) {
        super(dispatcher);
        this.getBinding().set('messages', Imm.Map({}));
        this.counter = 0;
    }

    getMessages() {
        return this.getBinding().get('messages');
    }
}

var addMessage = function addMessage(message) {
    var self = this;
    this.getBinding().update('messages', function(messages) {

        var id = self.counter++;
        setTimeout(function() {
            self.getBinding().update('messages', (messages) => messages.remove(id));
        }, 10000);

        return messages.set(id, message);

    });
};

FlashMessageStore.storeName = 'FlashMessageStore';
FlashMessageStore.handlers = {
    FLASH_MESSAGE_ADD: function(message) {
        addMessage.call(this, message);
    },

    FLASH_MESSAGE_REMOVE: function(id) {
        this.getBinding().update('messages', (messages) => messages.remove(id));
    },

    'REPLY_SUCCESS': function(body) {
        addMessage.call(this, {
            message: 'Odeslali jsme odpověď inzerentovi.'
        });
    },

    'AD_CREATE_SUCCESS': function(body) {
        addMessage.call(this, {
            message: 'Vytvořili jsme inzerát!'
        });
    }
};

module.exports = FlashMessageStore;