jest.autoMockOff();

describe('Dispatcher.dispatch', function() {


    it('should dispatch payload with params type & promise', function() {

        var Promise = require('es6-promise').Promise;
        var Dispatcher = require('../_dispatcher.js');
        var d = new Dispatcher();

        var payload;

        d.register(function(p) {
            payload = p;
        });
        d.dispatch('some.type', 'value');

        expect(payload.type).toBe('some.type');
        expect(payload.promise instanceof Promise);

    });


});