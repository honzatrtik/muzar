"use strict";

var debugBinding = require('debug')('Morearty binding');
var Morearty = require('morearty');
var serializer = require('./serializer.js');


var morearty;

module.exports = function(refresh) {

    if (!morearty || refresh) {

        var initialState = ((typeof window !== 'undefined') && (typeof window.serializedState === 'string'))
            ? serializer.unserialize(window.serializedState)
            : {};

        morearty = Morearty.createContext({
            initialState: initialState
        });

        debugBinding('Creating context with initial state', morearty.getBinding().toJS());
        morearty.getBinding().addListener(function(changes) {
            debugBinding('Change', changes.getPath(), morearty.getBinding().toJS());
        });
    }

    return morearty;

};
