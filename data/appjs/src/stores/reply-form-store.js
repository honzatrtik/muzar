"use strict";

var FormStore = require('./form-store.js');
var transform = require('../utils/symfony-form-error-transformer.js');
var Imm = require('immutable');

class ReplyFormStore extends FormStore {
    isLoading() {
        return this.getBinding().toJS('loading');
    }
}

ReplyFormStore.storeName = 'ReplyFormStore';
ReplyFormStore.handlers = {


    'REPLY': function() {
        this.getBinding().set('loading', true);
    },

    'REPLY_FAIL': function(data) {
        this.getBinding().set('loading', false);

        var self = this;
        self.clearErrors();

        Imm.fromJS(transform(data.errors)).forEach(function(errors, key) {
            errors.forEach(function(error) {
                self.addError(key, error);
            });
        });

    },

    'REPLY_SUCCESS': function(data) {
        this.getBinding().atomically()
            .set('loading', false)
            .set('data', Imm.Map({}))
            .set('errors', Imm.Map({}))
            .commit();
    },

    'ROUTE_CHANGED': function(state) {
        this.getBinding().atomically()
            .set('loading', false)
            .set('data', Imm.Map({}))
            .set('errors', Imm.Map({}))
            .commit();
    }


};

module.exports = ReplyFormStore;