"use strict";

class BaseStore {
    constructor(dispatcher) {
        this.dispatcher = dispatcher;
        if (!this.getContext().morearty) {
            throw new Error('"morearty" must be passed in context');
        }
        this.binding = this.getContext().morearty.getBinding().sub(this.constructor.name);
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