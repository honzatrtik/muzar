"use strict";

var Promise = require('../promise.js');
var BaseStore = require('./base-store.js');
var Imm = require('immutable');
//var Joi = require('joi');
//
//var schema = Joi.object().keys({
//    name: Joi.string().required().min(1),
//    category: Joi.array(Joi.string().min(1)).required().min(1),
//    allowSendingByMail: Joi.boolean(),
//    price: Joi.string(),
//    negotiatedPrice: Joi.boolean(),
//    description: Joi.string(),
//    contact: Joi.object().keys({
//        name: Joi.string().required().min(1),
//        phone: Joi.string().required().min(1),
//        email: Joi.string().email().required().min(1)
//    }).required()
//});
//


class AdFormStore extends BaseStore {

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

    validate() {
        if (!this.isDirty()) {
            return;
        }

        var self = this;

        self.getBinding().clear('errors');

        //var { error } = Joi.validate(self.get(), schema, {
        //    abortEarly: false
        //});
        //
        //if (error) {
        //    _.each(error.details, (detail) => { self.addError(detail.path, detail.message) });
        //}

        self.getBinding().set('dirty', false);
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


AdFormStore.storeName = 'AdFormStore';
AdFormStore.handlers = {
    'AD_FORM_RESET_PLACE': function() {
        this.getBinding().remove('data.contact.place');
    }
};
module.exports = AdFormStore;