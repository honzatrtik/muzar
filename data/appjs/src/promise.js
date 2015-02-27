var RSVP = require('rsvp');
RSVP.on('error', function(reason) {
    console.assert(false, reason);
});


module.exports = RSVP.Promise;