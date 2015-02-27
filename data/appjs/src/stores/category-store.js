"use strict";

var Promise = require('../promise.js');
var Imm = require('immutable');
var superagent = require('../superagent.js');
var BaseStore = require('./base-store.js');
var RouteStore = require('./route-store.js');
var TreeModel = require('tree-model');
var HttpError = require('../errors/http-error.js');


var req;
function load() {
    req && req.abort();
    req = superagent.get('/categories/tree');
    return new Promise(function(resolve, reject) {
        req.end(function(res) {
            if (res.ok) {
                resolve(res.body);
            } else {
                reject(res);
            }
        });
    });
}


function parseTree(data) {
    var tree = new TreeModel();

    // Add root 'node'
    var root = tree.parse({
        str_id: 'root',
        children: data
    });

    var map = {};
    root.walk(function(node) {
        map[node.model.str_id] = node;
    });

    return map;
}



class CategoryStore extends BaseStore {

    getItems() {
        return this.getBinding().get('items');
    }

    getMap() {
        if (!this.map) {
            this.map = parseTree(this.getBinding().toJS('items'));
        }
        return this.map;
    }

    getPath(category) {
        var node = this.getMap()[category];
        if (node) {
            return node.getPath().map(function(node) {
                return node.model;
            }).slice(1);
        }
        return [];
    }

    getActivePath() {
        var active = this.getActive();
        if (active) {
            return this.getPath(active);
        }
        return [];
    }

    getActive() {
        return this.getBinding().toJS('active');
    }
}

CategoryStore.storeName = 'CategoryStore';
CategoryStore.handlers = {
    'SERVER_INIT': function() {
        var self = this;
        return load().then(function(data) {
            self.getBinding().set('items', Imm.fromJS(data.data));
        });
    },

    'ROUTE_CHANGED': function() {
        var self = this;
        return this.dispatcher.waitForPromises(RouteStore, function() {
            var store = self.getStore(RouteStore);
            if (store.getRoute() == 'list') {
                var category = store.getParams().category;
                if (!self.getPath(category).length) {
                    throw new HttpError(404, 'Category not found: ' + category);
                }
                self.getBinding().set('active', category);
            }
            if (store.getRoute() == 'listAll' ) {
                self.getBinding().set('active', null);
            }
        });
    }
};

module.exports = CategoryStore;