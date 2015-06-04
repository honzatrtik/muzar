"use strict";

var hooks = require('hooks');

hooks.before('GET /suggestions -> 200', function(test, done) {
    test.request.query = {
        query: 'kyt'
    };
    done();
});

hooks.after('GET /suggestions -> 200', function(test, done) {
    done();
});
