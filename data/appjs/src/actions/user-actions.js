"use strict";

var Promise = require('../promise.js');
var superagent = require('../superagent.js');
var HttpError = require('../errors/http-error.js');
var config = require('../../config.js');

module.exports.loginAction = function loginAction(dispatcher, payload) {

    dispatcher.dispatch('LOGIN');

    var req = superagent.get('/oauth/v2/token').query({
        grant_type: 'password',
        username: payload.username,
        password: payload.password,
        client_id: config.api.clientId
    });

    var promise = new Promise(function(resolve, reject) {
        req.end(function(res) {
            if (res.ok) {
                resolve(res.body);
            } else {
                reject(res);
            }
        });
    });

    return promise.then(function(body) {
        return dispatcher.dispatch('LOGIN_SUCCESS', body);
    }).catch(function(res) {
        if (res.status == 400) {
            return dispatcher.dispatch('LOGIN_FAIL', res.body);
        } else {
            throw new HttpError(res.status);
        }
    });

};

module.exports.loginFormToggleAction = function loginFormnToggleAction(dispatcher, show) {

    return dispatcher.dispatch('LOGIN_FORM_TOGGLE', show);

};
