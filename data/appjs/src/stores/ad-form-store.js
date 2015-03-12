"use strict";

var FormStore = require('./form-store.js');

class AdFormStore  extends FormStore {
    isLoading() {
        return this.getBinding().toJS('loading');
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
        console.log(data);
    },
    'AD_CREATE_SUCCESS': function() {
        this.getBinding().set('loading', false);
    }
};
module.exports = AdFormStore;