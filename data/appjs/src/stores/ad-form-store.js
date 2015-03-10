"use strict";

var FormStore = require('./form-store.js');

class AdFormStore  extends FormStore {
}

AdFormStore.storeName = 'AdFormStore';
AdFormStore.handlers = {
    'AD_FORM_RESET_PLACE': function() {
        this.getBinding().remove('data.contact.place');
    }
};
module.exports = AdFormStore;