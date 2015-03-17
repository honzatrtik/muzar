"use strict";

var Promise = require('../promise.js');
var BaseStore = require('./base-store.js');
var Imm = require('immutable');


class FormStore extends BaseStore {

    constructor(dispatcher) {
        super(dispatcher);
        this.getBinding().atomically()
            .set('data', Imm.Map({}))
            .set('errors', Imm.Map({}))
            .set('dirty', true)
            .commit();
    }

    get(name, defaultValue = '') {
        var path = ['data'].concat(name ? name.split('.') : []);
        return this.getBinding().toJS(path) || defaultValue;
    }

    clearErrors() {
        this.getBinding().atomically()
            .set('errors', Imm.Map({}))
            .set('dirty', true)
            .commit()
    }

    getErrors(name) {
        if (name) {
            var path = name.split('.').join('.');
            return this.getBinding().get(['errors', path]) || Imm.List();
        }
        return this.getBinding().get(['errors']).reduce(function(previous, value) {
            return previous.concat(value);
        }, Imm.List());
    }

    addError(name, message) {
        this.getBinding().update('errors', function(map) {
            var list = map.get(name) ? map.get(name) : Imm.List([]);
            return map.set(name, list.push(message));
        });
    }

    isDirty() {
        return !!this.getBinding().toJS('dirty');
    }


    getHandler(name) {
        var self = this;
        var path = ['data'].concat(name ? name.split('.') : []).join('.');

        return function(event) {

            var value;
            if (event && event.target) {
                // Checkbox boolean support
                value = (event.target.type === 'checkbox' || event.target.type === 'radio')
                    ? event.target.checked
                    : event.target.value;
            } else {
                value = event; // Fallback - custom components
            }

            self.getBinding().atomically()
                .set(path, value)
                .set('dirty', true)
                .commit();

        };
    }

}


module.exports = FormStore;