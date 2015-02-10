var Dispatcher = require('./dispatcher/Dispatcher.js')();
var RouteStore = require('./stores/route-store.js');
var AdStore = require('./stores/ad-store.js');
var AdDetailStore = require('./stores/ad-detail-store.js');

Dispatcher.registerStore(RouteStore);
Dispatcher.registerStore(AdStore);
Dispatcher.registerStore(AdDetailStore);

module.exports = Dispatcher;