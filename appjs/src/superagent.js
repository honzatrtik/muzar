var superagent = require('superagent');
var apiConfig = require('./superagent-api-config')('http://localhost:3030');

apiConfig(superagent);

module.exports = superagent;