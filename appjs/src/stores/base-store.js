"use strict";

class BaseStore {
    constructor(dispatcher) {
        this.dispatcher = dispatcher;
        if (!this.getContext().moreartyContext) {
            throw new Error('"moreartyContext" must be passed in context');
        }
        this.binding = this.getContext().moreartyContext.getBinding().sub('store-' + this.constructor.name);
    }

    getBinding() {
        return this.binding;
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