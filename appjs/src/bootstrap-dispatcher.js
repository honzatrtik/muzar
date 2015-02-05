var Dispatchr = require('dispatchr')();
var RouteStore = require('./stores/route-store.js');
var AdStore = require('./stores/ad-store.js');
var AdDetailStore = require('./stores/ad-detail-store.js');

Dispatchr.registerStore(RouteStore);
Dispatchr.registerStore(AdStore);
Dispatchr.registerStore(AdDetailStore);

module.exports = Dispatchr;