"use strict";

class BaseStore {
    constructor(dispatcher) {
        this.dispatcher = dispatcher;
        this.binding = this.getContext().moreartyContext.getBinding().sub('store-' + this.constructor.name);
    }

    getContext() {
        return this.dispatcher.getContext();
    }

    getStore(store) {
        return this.dispatcher.getStore(store);
    }

    shouldDehydrate() {
        return false;
    }
}

module.exports = BaseStore;