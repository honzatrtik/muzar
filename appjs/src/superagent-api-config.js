"use strict";
module.exports = function (prefix) {

    return function (request) {

        var end = request.Request.prototype.end;
        request.Request.prototype.end = function(fn) {
            if (this.url[0] === '/') {
                this.url = prefix + this.url;
            }
            this.accept('application/json');

            end.bind(this)(fn);
        };


        return request;
    };

};