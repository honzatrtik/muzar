"use strict";

var Promise = require('../promise.js');
var superagent = require('../superagent.js');
var HttpError = require('../errors/http-error.js');


module.exports.flashMessageAddAction = function flashMessageAddAction(dispatcher, payload) {
    dispatcher.dispatch('FLASH_MESSAGE_ADD', payload);
};

