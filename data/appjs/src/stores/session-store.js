"use strict";

var Imm = require('immutable');
var BaseStore = require('./base-store.js');
var superagent = require('../superagent.js');
var config = require('../../config.js');


class SessionStore extends BaseStore {

    constructor(dispatcher) {
        super(dispatcher);

        var self = this;
        superagent.use(function(req) {
            var accessToken = self.getAccessToken();
            if (accessToken) {
                req.query({
                    access_token: accessToken,
                    client_id: config.api.clientId
                });
            }
        });
    }

    getAccessToken() {
        return this.getBinding().toJS('session.access_token');
    }

    getRefreshToken() {
        return this.getBinding().toJS('session.refresh_token');
    }

    isLoginForm() {
        return this.getBinding().toJS('loginForm');
    }

}

SessionStore.storeName = 'SessionStore';
SessionStore.handlers = {
    'LOGIN_SUCCESS': function(session) {
        this.binding.atomically()
            .set('session', Imm.Map(session))
            .set('loginForm', false)
            .commit();
    },
    'LOGIN_FORM_TOGGLE': function(show) {
        this.binding.set('loginForm', !!show);
    }
};

module.exports = SessionStore;