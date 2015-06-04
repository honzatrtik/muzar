"use strict";

var debug = require('debug')('LoginForm');
var FormStore = require('./form-store.js');

class LoginFormStore  extends FormStore {
    isLoading() {
        return this.getBinding().toJS('loading');
    }
}

LoginFormStore.storeName = 'LoginFormStore';
LoginFormStore.handlers = {
    'LOGIN': function() {
        this.getBinding().set('loading', true);
    },

    'LOGIN_SUCCESS': function() {
        this.getBinding().set('loading', false);
    },

    'LOGIN_FAIL': function(data) {
        this.getBinding().set('loading', false);
        this.clearErrors();
        this.addError('form', 'Přihlašovací údaje nejsou správné');
        this.getBinding().clear('data.password');
        debug(data);
    }
};

module.exports = LoginFormStore;