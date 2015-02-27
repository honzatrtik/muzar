"use strict";

var BaseStore = require('./base-store.js');
var CategoryStore = require('./category-store.js');
var RouteStore = require('./route-store.js');
var Promise = require('../promise.js');
var superagent = require('../superagent.js');
var HttpError = require('../errors/http-error.js');
var Imm = require('immutable');
var urlParse = require('url-parse');
var _ = require('lodash');

var req;
function load(params) {
    req && req.abort();
    req = superagent.get('/ads').query(params);
    return new Promise(function(resolve, reject) {
        req.end(function(res) {
            if (res.ok) {
                resolve(res.body);
            } else {
                reject(new HttpError(res.error.status, res.error.text));
            }
        });
    });
}



class AdStore extends BaseStore {
    getItems() {
        return this.getBinding().get('items') || Imm.List();
    }

    isLoading() {
        return this.getBinding().toJS('loading');
    }

    hasNextLink() {
        return this.getBinding().toJS('meta.nextLink');
    }

    getTotal() {
        return this.getBinding().toJS('meta.total') || null;
    }
}

AdStore.storeName = 'AdStore';
AdStore.handlers = {

    'ROUTE_CHANGED': function() {
        var self = this;
        return this.dispatcher.waitForPromises([CategoryStore, RouteStore], function() {

            var routeStore = self.getStore(RouteStore);
            var binding = self.getBinding();

            if (routeStore.getRoute() == 'list') {

                var store = self.getStore(CategoryStore);
                binding.set('loading', true);

                var params = {};
                var category = store.getActive();
                if (category) {
                    params.category = category;
                }

                return load(params).then(function(data) {
                    binding.atomically()
                        .set('loading', false)
                        .set('items', Imm.fromJS(data.data))
                        .set('meta', Imm.fromJS(data.meta))
                        .commit();
                });
            } else {
                binding.atomically()
                    .set('loading', false)
                    .set('items', Imm.List())
                    .set('meta', Imm.List())
                    .commit();
            }


        });
    },

    'AD_LOAD_NEXT': function() {
        var self = this;
        var store = self.getStore(CategoryStore);
        var binding = self.getBinding();

        binding.set('loading', true);
        var params = urlParse(binding.toJS('meta.nextLink'), true).query;

        return load(params).then(function(data) {
            binding.atomically()
                .set('loading', false)
                .set('items', binding.get('items').concat(Imm.fromJS(data.data)))
                .set('meta', Imm.fromJS(data.meta))
                .commit();
        });

    }
};

module.exports = AdStore;