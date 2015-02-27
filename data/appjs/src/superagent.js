var superagent = require('superagent');
//var apiConfig = require('./superagent-api-config')('http://localhost:3030');
var apiConfig = require('./superagent-api-config')('http://127.0.0.1:8080/api');

apiConfig(superagent);

module.exports = superagent;