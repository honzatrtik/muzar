"use strict";

var Promise = require('../promise.js');
var superagent = require('../superagent.js');
var HttpError = require('../errors/http-error.js');


module.exports.adCreateAction = function adCreateAction(dispatcher, payload) {

    dispatcher.dispatch('AD_CREATE');

    if (payload.category) {
        payload.category = payload.category[payload.category.length - 1];
    }

    var promise = superagent.post('/ads').send(payload).promise();

    return promise.then(function(body) {
        return dispatcher.dispatch('AD_CREATE_SUCCESS', body);
    }).catch(function(res) {
        if (res.status == 400) {
            return dispatcher.dispatch('AD_CREATE_FAIL', res.body);

        } else {
            throw new HttpError(res.status);
        }
    });

};

