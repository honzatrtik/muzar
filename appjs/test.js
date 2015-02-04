'use strict';

//var Imm = require('immutable');
//var Morearty = require('morearty');
var Dispatcher = require('flux').Dispatcher;

//var Ctx = Morearty.createContext({
//    initialState: {
//        numbers: [2, 5, 6, 8]
//    }
//});







var dispatcher = new Dispatcher();
var actions = require('./src/route-actions.js')(dispatcher);


dispatcher.register(function(action) {
    var type = action.type;
    action.promise.then(function(data) {
        console && console.log('Dispatch', '[' + type + ']', data);
    });
});



var Handler = require('./src/handler/handler.js');


var h = new Handler();
h.register(dispatcher);

h.addHandler('navigate', function(promise) {
    promise.then(function(url) {
        actions.changeUrl(url);
    }).catch(h.errorHandler);
});



actions.navigate('http://seznam.cz/');
actions.navigate('test3');





