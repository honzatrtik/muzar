function HttpError(status, message) {
    this.status = status;
    this.message = message;
    this.stack = Error().stack;
}

HttpError.prototype = Object.create(Error.prototype);
HttpError.prototype.name = 'HttpError';

module.exports = HttpError;