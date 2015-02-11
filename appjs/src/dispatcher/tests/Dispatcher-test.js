"use strict";

var debug = require('debug')('Dispatcher test');
var Promise = require('es6-promise').Promise;
var assert = require('assert');

var Store = function(dispatcher) {
    this.dispatcher = dispatcher;
    this.after = null;
};
Store.storeName = 'Store';


var Store2 = function(dispatcher) {
    this.dispatcher = dispatcher;
};
Store2.storeName = 'Store2';




describe('Dispatcher', function() {

    it('waits for promise to end', function(done) {


        Store.handlers = {
            'test': function() {
                var self = this;
                return new Promise(function(resolve, reject) {
                    setTimeout(function() {
                        self.after = 'after';
                        resolve();
                        done();
                    }, 10);
                });
            }
        };

        var Dispatcher = require('../Dispatcher.js')();
        Dispatcher.registerStore(Store);
        var dispatcher = new Dispatcher({});

        var promise = dispatcher.dispatch('test', {});
        debug(promise);

        assert.equal(dispatcher.getStore(Store).after, null);
        assert(promise.then instanceof Function);

        promise.then(function() {
            expect(dispatcher.getStore(Store).after).toEqual('after');
        });

    });

    it('waitForPromises waits for promises to end', function(done) {


        Store.handlers = {
            'test': function() {
                var self = this;
                console.log('store, before');
                return new Promise(function(resolve, reject) {
                    setTimeout(function() {
                        resolve();
                        done();
                    }, 10);
                });
            }
        };

        Store2.handlers = {
            'test': function() {
                var self = this;
                this.dispatcher.waitForPromises(Store, function() {
                });
            }
        };

        var Dispatcher = require('../Dispatcher.js')();
        Dispatcher.registerStore(Store2);
        Dispatcher.registerStore(Store);

        var dispatcher = new Dispatcher({});
        var promise = dispatcher.dispatch('test', {});


    });

});