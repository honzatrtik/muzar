"use strict";

var FormStore = require('./form-store.js');
var transform = require('../utils/symfony-form-error-transformer.js');
var Imm = require('immutable');

class AdFormStore  extends FormStore {
    isLoading() {
        return this.getBinding().toJS('loading');
    }

    getCreatedAd() {
        return this.getBinding().get('createdAd');
    }
}

AdFormStore.storeName = 'AdFormStore';
AdFormStore.handlers = {

    'AD_FORM_RESET_PLACE': function() {
        this.getBinding().remove('data.contact.place');
    },

    'AD_CREATE': function() {
        this.getBinding().set('loading', true);
    },

    'AD_CREATE_FAIL': function(data) {
        this.getBinding().set('loading', false);

        var self = this;
        self.clearErrors();
        Imm.fromJS(transform(data.errors)).forEach(function(errors, key) {
            errors.forEach(function(error) {
                self.addError(key, error);
            });
        });

    },

    'AD_CREATE_SUCCESS': function(data) {
        this.getBinding().atomically()
            .set('loading', false)
            .set('createdAd', Imm.Map(data.data))
            .commit();

        var routerContainer = require('../router-container.js').get();
        routerContainer.transitionTo('createSuccess');
    }
};

module.exports = AdFormStore;