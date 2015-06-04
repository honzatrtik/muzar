/**
 * Copyright 2014, Yahoo! Inc.
 * Copyrights licensed under the New BSD License. See the accompanying LICENSE file for terms.
 */
'use strict';

var Promise = require('../promise.js');
var debug = require('debug')('Dispatcher:Action');

function Action(name, payload) {
    this.name = name;
    this.payload = payload;
    this._handlers = null;
    this._isExecuting = false;
    this._isCompleted = null;
}

/**
 * Gets a name from a store
 * @method getStoreName
 * @param {String|Object} store The store name or class from which to extract
 *      the name
 * @returns {String}
 */
Action.prototype.getStoreName = function getStoreName(store) {
    if ('string' === typeof store) {
        return store;
    }
    return store.storeName;
};

/**
 * Executes all handlers for the action
 * @method execute
 * @param {Function[]} handlers A mapping of store names to handler function
 * @throws {Error} if action has already been executed
 */
Action.prototype.execute = function execute(handlers) {
    if (this._isExecuting) {
        throw new Error('Action is already dispatched');
    }
    var self = this;
    this._promises = {};
    this._handlers = handlers;
    this._isExecuting = true;
    this._isCompleted = {};
    Object.keys(handlers).forEach(function handlersEach(storeName) {
        self._callHandler(storeName);
    });
    var promises = Object.keys(this._promises).map(function (key) {
        return self._promises[key];
    });

    return Promise.all(promises);
};

/**
 * Calls an individual store's handler function
 * @method _callHandler
 * @param {String} storeName
 * @private
 * @throws {Error} if handler does not exist for storeName
 */
Action.prototype._callHandler = function callHandler(storeName) {
    var self = this,
        handlerFn = self._handlers[storeName];
    if (!handlerFn) {
        throw new Error(storeName + ' does not have a handler for action ' + self.name);
    }
    if (self._isCompleted[storeName]) {
        return;
    }
    self._isCompleted[storeName] = false;
    debug('executing handler for ' + storeName);
    var result = handlerFn(self.payload, self.name);
    self._isCompleted[storeName] = true;
    self._promises[storeName] = Promise.resolve(result);
};

/**
 * Waits until all stores have finished handling an action and then calls
 * the callback
 * @method waitFor
 * @param {String|String[]|Constructor|Constructor[]} stores An array of stores as strings or constructors to wait for
 * @param {Function} callback Called after all stores have completed handling their actions
 * @throws {Error} if the action is not being executed
 */
Action.prototype.waitFor = function waitFor(stores, callback) {
    var self = this;
    if (!self._isExecuting) {
        throw new Error('waitFor called even though there is no action being executed!');
    }
    if (!Array.isArray(stores)) {
        stores = [stores];
    }

    debug('waiting on ' + stores.join(', '));
    stores.forEach(function storesEach(storeName) {
        storeName = self.getStoreName(storeName);
        self._callHandler(storeName);
    });

    return callback();
};

Action.prototype.waitForPromises = function waitForPromises(stores, callback) {

    var self = this;
    if (!self._isExecuting) {
        throw new Error('waitForPromise called even though there is no action being executed!');
    }
    if (!Array.isArray(stores)) {
        stores = [stores];
    }

    return this.waitFor(stores, function() {

        debug('waiting on promises ' + stores.join(', '));
        var promises = [];
        stores.forEach(function storesEach(storeName) {
            var key = self.getStoreName(storeName);
            promises.push(self._promises[key]);
        });

        return Promise.all(promises).then(callback);

    });

};


module.exports = Action;
