var debugBinding = require('debug')('Morearty binding');
var Morearty = require('morearty');

var window = window || false;
var morearty = Morearty.createContext({
    initialState: (window ? (serializer.unserialize(window.serializedState) || {}) : {})
});

morearty.getBinding().addListener(function(changes) {
    debugBinding('Change', changes.getPath(), morearty.getBinding().toJS());
});

module.exports = morearty;
