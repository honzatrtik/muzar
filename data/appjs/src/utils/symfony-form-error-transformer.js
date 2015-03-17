"use strict";

var transform = function transform(errors, path, initial) {
    initial = initial || {};
    path = path || [];
    if (errors.children) {
        for (var name in errors.children) {
            if (errors.children.hasOwnProperty(name)) {

                var joined = path.concat([name]).join('.');

                if (errors.children[name].errors && errors.children[name].errors.length) {
                    initial[joined] = errors.children[name].errors;
                }
                if (errors.children[name].children) {

                    transform(errors.children[name], path.concat([name]), initial);
                }
            }
        }
    }
    return initial;
};



module.exports = transform;