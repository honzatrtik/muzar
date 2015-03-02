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
        return this.getMeta('nextLink');
    }

    getTotal() {
        return this.getMeta('total');
    }

    getMeta(name) {
        var path = ['meta'];
        if (name) {
            path = path.concat(name.split('.'));
        }
        return this.getBinding().toJS(path) || null;
    }
}

AdStore.storeName = 'AdStore';
AdStore.handlers = {

    'ROUTE_CHANGED': function() {
        var self = this;
        return this.dispatcher.waitForPromises([CategoryStore, RouteStore], function() {

            var routeStore = self.getStore(RouteStore);
            var binding = self.getBinding();

            var params;

            if (_.indexOf(['list', 'listAll'], routeStore.getRoute()) !== -1) {

                var store = self.getStore(CategoryStore);
                binding.set('loading', true);

                params = {};
                var category = store.getActive();
                if (category) {
                    params.category = category;
                }


                return load(params).then(function (data) {
                    binding.atomically()
                        .set('loading', false)
                        .set('items', Imm.fromJS(data.data))
                        .set('meta', Imm.fromJS(data.meta))
                        .commit();
                });

            } else if (_.indexOf(['search'], routeStore.getRoute()) !== -1) {

                binding.set('loading', true);

                params = {};
                var query = routeStore.getQuery('query');

                if (query) {
                    params.query = query;
                }

                return load(params).then(function (data) {
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
                    .set('meta', Imm.Map())
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