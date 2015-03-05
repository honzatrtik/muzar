"use strict";

var Promise = require('../promise.js');
var superagent = require('../superagent.js');
var AdFormStore = require('../stores/ad-form-store.js');
var HttpError = require('../errors/http-error.js');



module.exports.adFormSubmitAction = function adFormSubmitAction(dispatcher) {

    var store = dispatcher.getStore(AdFormStore);
    store.validate();

    if (!store.getErrors().size) {

        dispatcher.dispatch('AD_FORM_SUBMIT');

        var values = store.get();

        var req = superagent.post('/ads').send(values);
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
            return dispatcher.dispatch('AD_FORM_SUBMIT_SUCCESS', body);
        });

    }

};
