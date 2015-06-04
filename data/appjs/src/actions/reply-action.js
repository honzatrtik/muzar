"use strict";

var Promise = require('../promise.js');
var superagent = require('../superagent.js');
var HttpError = require('../errors/http-error.js');


export default function replyAction(dispatcher, data) {

    dispatcher.dispatch('REPLY');

    var promise = superagent.post('/ads/' + data.id + '/replies').send(data.payload).promise();

    return promise.then(function(body) {

        return dispatcher.dispatch('REPLY_SUCCESS', body).then(function() {

            var routerContainer = require('../router-container.js').get();
            routerContainer.transitionTo('detail', {
                id: data.id
            });
        });

    }).catch(function(res) {

        if (res instanceof Error) {
            throw res;
        }

        if (res.status == 400) {
            return dispatcher.dispatch('REPLY_FAIL', res.body);

        } else {
            throw new HttpError(res.status);
        }
    });

};

