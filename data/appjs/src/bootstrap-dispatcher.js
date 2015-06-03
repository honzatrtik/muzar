var Dispatcher = require('./dispatcher/Dispatcher.js')();
var RouteStore = require('./stores/route-store.js');
var AdStore = require('./stores/ad-store.js');
var AdDetailStore = require('./stores/ad-detail-store.js');
var CategoryStore = require('./stores/category-store.js');
var ReplyFormStore = require('./stores/reply-form-store.js');
var AdFormStore = require('./stores/ad-form-store.js');
var LoginFormStore = require('./stores/login-form-store.js');
var SessionStore = require('./stores/session-store.js');
var GeoStore = require('./stores/geo-store.js');
var FlashMessageStore = require('./stores/flash-message-store.js');

Dispatcher.registerStore(RouteStore);
Dispatcher.registerStore(SessionStore);
Dispatcher.registerStore(AdStore);
Dispatcher.registerStore(AdDetailStore);
Dispatcher.registerStore(CategoryStore);
Dispatcher.registerStore(AdFormStore);
Dispatcher.registerStore(ReplyFormStore);
Dispatcher.registerStore(LoginFormStore);
Dispatcher.registerStore(GeoStore);
Dispatcher.registerStore(FlashMessageStore);

module.exports = Dispatcher;