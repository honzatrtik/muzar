'use strict';


class Handler {

    constructor() {
        this._listeners = [];
    }

    addHandler(type, callback) {
        this._listeners[type] = callback;
    }

    register(dispatcher) {
        this._dispatcher = dispatcher;
        this._dispatchToken = this._dispatcher.register(this.handler.bind(this));
        return this._dispatchToken;
    }

    unregister() {
        this._dispatcher.unregister(this._dispatchToken);
        this._dispatcher = null;
    }

    getDispatchToken() {
        return this._dispatchToken;
    }

    handler(action) {
        var type = action.type;
        var callback = this._listeners[type];
        if (callback) {
            callback(action.data);
        }
    }

    errorHandler(e) {
        setTimeout(function() {
            throw e;
        });
    }
}



module.exports = Handler;
