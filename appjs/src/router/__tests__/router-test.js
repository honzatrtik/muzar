jest.autoMockOff();

describe('Router', function() {


    it('handles "router.navigate"', function() {

        var Promise = require('es6-promise').Promise;
        var Dispatcher = require('../../_dispatcher/_dispatcher.js');
        var Router = require('../router.js');


        var d = new Dispatcher();
        var r = new Router();
        r.register(d);



    });


});