"use strict";

var transform = function transform(errors) {
    var result = {};

    errors.forEach(function(error) {
        var path = error.property_path;
        var messages = [error.message];
        result[path] = result[path]
            ? result[path].concat(messages)
            : messages;
    });

    return result;
};



module.exports = transform;